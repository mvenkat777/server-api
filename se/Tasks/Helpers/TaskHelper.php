<?php

namespace Platform\Tasks\Helpers;

use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Tasks\Repositories\Contracts\TagRepository;
use Platform\Tasks\Repositories\Contracts\CategoryRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\App\Helpers\Helpers;
use Platform\Tasks\Mailer\TaskMailer;

use Platform\Tasks\Commands\CreateTagCommand;
use Platform\Tasks\Commands\CreateCategoryCommand;
use Platform\Users\Commands\CreateUserCommand;
use Carbon\Carbon;
use Platform\Tasks\Transformers\MetaTaskTransformer;

class TaskHelper 
{
	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @var Platform\Users\Repositories\Contracts\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TagRepository
	 */
	protected $tagRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Mailer\TaskMailer
	 */
	protected $taskMailer;

	public function __construct(UserRepository $userRepository,
								DefaultCommandBus $commandBus,
								TagRepository $tagRepository,
								CategoryRepository $categoryRepository,
								TaskRepository $taskRepository,
								TaskMailer $taskMailer)
	{
		$this->commandBus = $commandBus;		
		$this->userRepository = $userRepository;
		$this->tagRepository = $tagRepository;
		$this->categoryRepository = $categoryRepository;
		$this->taskRepository = $taskRepository;
		$this->taskMailer = $taskMailer;
	}

	/**
	 * Get details of assignee by email or create one user if not exist
	 * [AddTaskFollower, CreateTask]
	 * @param  string $email
	 * @return array       
	 */
	public function getAssigneeDetails($email)
	{
		$user = $this->getUser($email);

		if(!$user){
			$tempPass = Helpers::makeTemporaryPassword($email);
			
			$origin = $_SERVER['HTTP_ORIGIN'];
			$data = [
				'display_name' => $email,
				'email' => $email,
				'password' => $tempPass,
				'is_password_change_required' => true,
				'admin' => true
			];
			$user = $this->commandBus->execute(new CreateUserCommand($data, true));
			
			$this->taskMailer->taskForNewUser($user, ['url' => $origin.'/#/auth/login', 'password' => $tempPass]);
		}

		return $user->id;
		
	}

	/**
	 * Get user by email
	 * 
	 * @param  string] $data  
	 * @param  string $column
	 * @return App\User        
	 */
	private function getUser($data, $column = 'email')
	{
		if($column == 'email')
			return $this->userRepository->getByEmail($data);
		else 
			return $this->userRepository->getUserById($data);
	}

	/**
	 * Change tag title to tagid
	 * [CreateTask, UpdateTask, AddTag]
	 * @param  array $tags
	 * @return array
	 */
	public function changeToTagId(array $tags){
		for ($i=0; $i < count($tags); $i++) {
			if($this->tagRepository->getTagByTitle($tags[$i]) == NULL){
				$newTag = $this->commandBus->execute(new CreateTagCommand($tags[$i]));
				$tags[$i] = $newTag->id;
			} 
			else{
				$tags[$i] = $this->tagRepository->getTagByTitle($tags[$i])->id;
			}
		}
		return $tags;
	}

	/**
	 * Change category title to id
	 * [createTask, UpdateTask]
	 * @param  string $category
	 * @return string          
	 */
	public function changeToCategoryId($category){
		if($this->categoryRepository->getCategoryByTitle($category) == NULL){
			$newCategory = $this->commandBus->execute(new CreateCategoryCommand($category));
			return $newCategory->id;
		}
		$category = $this->categoryRepository->getCategoryByTitle($category)->id;
		return $category;
	}

	/**
	 * Change category title to id for filtering of tasks
	 * [FilterTask]
	 * @param  array $categories [Array of Title]
	 * @return array             [Array of id]
	 */
	public function changeCategoryForFilter($categories)
	{
		$categoryIdArr = [];
		for ($i=0; $i < count($categories); $i++) { 
			$category = $this->categoryRepository->getCategoryByTitle($categories[$i]);
			if($category == NULL){
				continue;
			}
			else{
				array_push($categoryIdArr, $category->id);
			}
		}
		return $categoryIdArr;
	}

	/**
	 * Change tag title to id and remove if title present
	 * [filterTask]
	 * @param  array $tags
	 * @return array      
	 */
	public function changeTagsForFilter($tags)
	{
		$tagsIdArr = [];
		for ($i=0; $i < count($tags); $i++) { 
			$tag = $this->tagRepository->getTagByTitle($tags[$i]);
			if($tag){
				array_push($tagsIdArr, $tag->id);
			}
		}
		return $tagsIdArr;
	}

	/**
	 * Convert tag to tag id
	 * [used in removeTag]
	 * @param  string $tag
	 * @return string     
	 */
	public function convertTag($tag)
	{
		if($this->tagRepository->getTagByTitle($tag) == NULL){
			throw new SeException("Invalid Tag ".$tag);
		} 
		$tag = $this->tagRepository->getTagByTitle($tag)->id;
		return $tag;
	}

	/**
	 * For sending emails for assigned task when first time loggedin
	 * @param  App\User $user
	 * @return mixed      
	 */
	public function sendAssignedTask($user)
	{
		$tasks = $this->taskRepository->getTaskByType('assigned', $user);
		$origin = $_SERVER['HTTP_ORIGIN'];
		foreach ($tasks as $key => $task) {
			$data = [
                        'taskId' => $task->id,
                        'assigneName' => $task->assignee->displayName,
                        'assignersName' => $task->creator->displayName,
                        'name' => $task->title,
                        'created_at' => $task->created_at->toDateTimeString(),
                        'link' => $origin.'/#/tasks/list?q=assigned'
                    ];
            
			$this->taskMailer->taskWasCreated($user, $data);
		}
	}

	/**
	 * @param string $name [<assigned/submitted etc...>]
	 * @return integer [Task Status Id]
	 */
	public static function getStatusId($name)
	{
		$statuses = \App\TaskStatus::select('id','status')->get()->toArray();
        $result = [];
        foreach ($statuses as $key => $status) {
            $result[$status['status']] = $status['id']; 
        }
        return $result[$name];
	}

	public static function getAssigneeStatusId($name)
	{	
		return \App\TaskAssigneeStatus::where('status', '=', $name)->first()->id;
	}

	public static function getPriorityId($priority)
	{
		$priorityDB = \App\Priority::where('priority', '=', strtolower($priority))->first();
		if($priorityDB)
			return $priorityDB->id;
		else
			return false;
	}

	/**
	 * Get array of userIds that will receive the notification
	 * 
	 * @param  App\Task $task        
	 * @param  UserId $nonReceiver 
	 * @return array              
	 */
	public static function getTaskNotificationReceivers($task, $nonReceiver)
	{
		$followers = array_column($task->followers->toArray(), 'follower_id');
		$assignee = $task->assignee_id;
		$taskCreator = $task->creator_id;
		$receivers = array_merge($followers, [$assignee, $taskCreator]);
		if(is_array($nonReceiver)){
			return array_unique(array_diff($receivers, $nonReceiver));
		}
		return array_unique(array_diff($receivers, [$nonReceiver]));
	}

    public function groupTasks($tasks)
    {
        $pending = [];
        $todayTasks = [];
        $thisWeek = [];
        $upcoming = [];
        $today = Carbon::now()->toDateString();
        $week = Carbon::now()->addWeek()->toDateString();
        foreach($tasks as $task) {
            $dueDate = Carbon::parse($task->due_date)->toDateString();
            if($dueDate < $today) {
                $pending[] = (new MetaTaskTransformer)->transform($task);
            } elseif($dueDate === $today) {
                $todayTasks[] = (new MetaTaskTransformer)->transform($task);
            } elseif($dueDate > $today && $dueDate < $week) {
                $thisWeek[] = (new MetaTaskTransformer)->transform($task);
            } else {
                $upcoming[] = (new MetaTaskTransformer)->transform($task);
            }
        }

        return [
            'pending' => $pending,
            'today' => $todayTasks,
            'thisWeek' => $thisWeek,
            'upcoming' => $upcoming
        ];
    }

    /**
     * Check task belongs to which typw
     *
     * @param $task Model
     * @return string
     */
    public static function checkType($task)
    {
        $today = Carbon::now()->toDateString();
        $week = Carbon::now()->addWeek()->toDateString();
        $dueDate = Carbon::parse($task->due_date)->toDateString();

        if($dueDate < $today) {
            return 'pending';
        } elseif($dueDate === $today) {
            return 'today';
        } elseif($dueDate > $today && $dueDate < $week) {
            return 'thisWeek';
        } else {
            return 'upcoming';
        }
    }
}
