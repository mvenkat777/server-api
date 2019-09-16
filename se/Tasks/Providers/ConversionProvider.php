<?php
namespace Platform\Tasks\Providers;

use Carbon\Carbon;
use Platform\App\Exceptions\SeException;

class ConversionProvider
{
	/**
	 * Convert task for bulk upload of task
	 * 
	 * @param  array $task
	 * @return array      
	 */
	public static function convertToQuickTask($task)
	{
		$tags = explode(',', $task['tags']);
		// $category = explode(',', $task['category']);

		return [
			'title' => $task['title'],
	 		'category' => $task['category'],
	 		'assignee' => $task['assignee'],
	 		'dueDate' => $task['task_deadline'],
	 		'priorityId' => $task['priority'],
	 		'tags' => $tags,
	 		'description' => $task['description']
		];
	}

	/**
	 * Change categories array to string title for upload task
	 * 
	 * @param  array $categories
	 * @return string            
	 */
	public static function changeCategoriesToCategory($categories){
		foreach ($categories as $category) {
			return $category['title'];
		}
	}

	/**
	 * Convert tags array to title array for upload task
	 * 
	 * @param  array $tags
	 * @return array      
	 */
	public static function convertToTagTitles($tags){
		$result = [];
		foreach ($tags as $tag) {
			$result[] = $tag['title'];
		}
		return $result;
	}

	/**
	 * Get today date in US format
	 * @return string
	 */
	public static function getTodayDate()
	{
		return Carbon::now()->toDateTimeString();
		// $date = new \DateTime('today');
		// $date = $date->format('m-d-Y H:i:s');

		// return $date;
	}

	/**
	 * Return data required to create task
	 * 
	 * @param  object $task   
	 * @param  object $newTask
	 * @return array         
	 */
	public static function createTaskData($task, $newTask)
	{
		$tags = [];
		foreach ($task->tags as $tag) {
			$tags[] = $tag->title;
		}

		return [
			'title' => $task->title,
			'description' => $task->description,
			'dueDate' => $newTask->dueDate,
			'category' => $task->categories[0]->title,
			'tags' => $tags,
			'assignee' => $newTask->assignee
		];
	}

	/**
	 * Return the data required for adding comment
	 * 
	 * @param object $comment
	 * @param object $command
	 * @return array
	 */
	public static function addCommentData($comment, $command)
	{
		return [
			'data' => '',
			'type' => $comment->type,
			'taskId' => $command->taskId,
			'owner' => $command->owner,
			'ownerDetails' => $command->ownerDetails,
			'type' => $command->type,
			'data' => $command->data
		];
	}

	/**
	 * Return the data required to add follower task
	 * 
	 * @param object $follower
	 * @param object $newTask 
	 * @return array
	 */
	public static function addTaskFollowerData($follower, $newTask)
	{
		return [
			'taskId' => $newTask->id,
			'follower' => $follower->follower,
			'followerDetails' => json_decode($follower->followerDetails)
		];
	}

	/**
     * Return all task statuses in array format
     * [Used in EloquentTaskRepository for getFilterType]
     * @return array
     */
    public static function getAllStatus()
    {
        $statuses = \App\TaskStatus::select('id','status')->get()->toArray();
        $result = [];
        foreach ($statuses as $key => $status) {
            $result[$status['status']] = $status['id']; 
        }
        return $result;
    }

}