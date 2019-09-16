<?php

namespace Platform\Tasks\Handlers\Commands;

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Commands\AddAttachmentCommand;
use Platform\Tasks\Commands\AddCheckListCommand;
use Platform\Tasks\Commands\CreateTagCommand;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Providers\ConversionProvider;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Events\TaskWasCreated;
use Platform\App\Helpers\Helpers;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\App\Wrappers\GoogleCalender;

use Platform\Users\Commands\CreateUserCommand;
use App\Style;
use Platform\Tasks\Repositories\Contracts\GoogleCalendarRepository;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class CreateTaskCommandHandler implements CommandHandler
{
    use DispatchesJobs;
	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    /**
     * @var DefaultNotificationBus
     */
    protected $defaultNotificationBus;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepositor
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\GoogleCalendarRepository
	 */
	protected $googleCalendarRepo;

	/**
	 * @var Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;

	/**
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
	 * @param DefaultCommandBus  $commandBus        
	 * @param TaskRepository     $taskRepository    
	 * @param Guard              $auth              
	 * @param TaskValidator      $taskValidator     
	 * @param TaskHelper         $taskHelper        
	 * @param TagRepository      $tagRepository     
	 * @param DefaultRuleBus     $defaultRuleBus
	 */
	public function __construct(DefaultCommandBus $commandBus,
								TaskRepository $taskRepository,
								Guard $auth,
								TaskValidator $taskValidator,
								TaskHelper $taskHelper, 
                                GoogleCalendarRepository $googleCalendarRepo,
								DefaultRuleBus $defaultRuleBus)
	{
		$this->commandBus = $commandBus;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
		$this->defaultRuleBus = $defaultRuleBus;
        $this->googleCalendarRepo = $googleCalendarRepo;
	}

	/**
	 * @param  CreateTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{

		if($command->dueDate->toDateString() < Carbon::now()->toDateString() && !$command->skipCheck){
			if(!is_null($command->rowNumber)){
				return "Due date is less than today at line $command->rowNumber 'task deadline' $command->dueDate";
			}
			throw new SeException('Due date is less than today', 422, 786108);
		}

		if(!is_null($command->tags)){
			$command->tags = $this->taskHelper->changeToTagId($command->tags);
		}
		$command->category = $this->taskHelper->changeToCategoryId($command->category);

		if(!Helpers::isSeEmail($command->assignee)){
			if(!is_null($command->rowNumber)){
				return "Assignee does not Exists at line $command->rowNumber 'assignee' $command->assignee";
			}
			throw new SeException('Assignee does not Exists in SE', 422, 786119);
		}

		$command->assignee = $this->taskHelper->getAssigneeDetails($command->assignee);
		$task = $this->taskRepository->createTask($command);

        if($this->isMilestone($task) && getenv("APP_ENV") == 'production') {
            $this->addToGoogleCalender($task);
        }

        if(!is_null($task->tnaItem)) {
            $job = (new DefaultRuleBusJob($task, $task->tnaItem->tna->representor, 'CreateNewTask'));
            $this->dispatch($job);
        } else {
            $job = (new DefaultRuleBusJob($task, \Auth::user(), 'CreateNewTask'));
            $this->dispatch($job);
        }
		return $task;
	}

    /** Check if task is a milestone or not
     *
     * @param Model $task
     * @return boolean
     */
    private function isMilestone($task)
    {
        $tnaItem = $task->tnaItem;
        return !is_null($tnaItem) && $tnaItem->is_milestone;
    }
    
    /**
     * Add event to google Calendar
     *
     * @param Model $task
     * @return $event
     */
    private function addToGoogleCalender($task)
    {
        $date = $task->due_date->toDateString();
        $description = $this->getDescription($task);
        $event = (new GoogleCalender)->setDescription($description)->insert($task->assignee->email, $this->getTitle($task), $date);

        if($event) {
            $googleCalendar = $this->googleCalendarRepo->save([
                'eventId' => $event->getId(), 
                'calendarId' => 'sparklefashion.net_gh6ldr1l2me1m0h46s1pjk8go8@group.calendar.google.com'
            ]);
            $task->google_calendar_id = $googleCalendar->id;
            $task->save();
        }
    }

    /**
     * Get title for google calendar event
     *
     * @param   Model $task
     * @rerurn  string
     */
    private function getTitle($task)
    {
        $title = '';

        if(!is_null($task->tnaItem)) {
            $title = $task->tnaItem->tna->customer->name.' : ';
        }
        return $title.$task->title;
    }

    /**
     * Get Description for google calendar event
     *
     * @param   Model $task
     * @rerurn  string
     */
    private function getDescription($task)
    {
        $taskLink = $this->getHost().'/#/tasks/'.$task->id;
        $links = "\nTask link - <a href='$taskLink'>$taskLink</a>\n\n";
        $description = '';
        if(!is_null($task->tnaItem)) {
            $tna = $task->tnaItem->tna;
            $links = $links."TNA Link - ".$this->getHost().'/#/tNa/edit/'.$tna->id; 

            $style = Style::where('tna_id', $tna->id)->first();
            if(!is_null($style)) {
                $line = $style->line;
            }
            if(!is_null($tna->customer)) {
                $description = $description.$tna->customer->name.' / ';
            } 
            if(!is_null($style) && isset($style->line->name)) {
                $description = $description.$style->line->name.' / '.$style->name.' / ';
            } 
        }
        return $description.$task->title."\n\n".$links;
    }

    /**
     * Get frontend host url for link of description
     *
     * @return string
     */
    private function getHost()
    {
        if(getenv('APP_ENV') === 'local') {
            return 'http://platform.dev';
        } elseif(getenv('APP_ENV') === 'staging') {
            return 'http://platform.sourc.in';
        } else {
            return 'http://platform.sourceeasy.com';
        }
    }

}
