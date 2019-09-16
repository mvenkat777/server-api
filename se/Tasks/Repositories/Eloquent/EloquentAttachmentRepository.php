<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Platform\Tasks\Repositories\Contracts\AttachmentRepository;
use Platform\App\Repositories\Eloquent\Repository;

use Platform\App\Exceptions\SeException;

class EloquentAttachmentRepository extends Repository implements AttachmentRepository
{
	/**
	 * @return string
	 */
	public function model()
	{
		return 'App\TaskAttachment';
	}

	/**
	 * @param  string $taskId 
	 * @return mixed         
	 */
	public function getAttachmentByTaskId($taskId)
	{
		return $this->model->where('task_id', '=', $taskId)->get();
	}

	/**
	 * @param  string $taskId 
	 * @param  string $type   
	 * @return mixed    
	 */
	public function getAttachmentByTaskIdAndType($taskId, $type)
	{
		return $this->model->where('task_id', '=', $taskId)
			->where('type', '=', $type)
			->get();
	}

	/**
	 * @param  string $taskId 
	 * @return boolean         
	 */
	public function deleteForTasks($taskId)
	{
		return $this->model->where('task_id', '=', $taskId)
							->delete();
	}

	/**
	 * @param  int 	$id 
	 * @return boolean
	 */
	public function deleteAttachment($id)
	{
		return $this->delete($id);
	}

	/**
	 * @param  Object $command 
	 * @return mixed
	 */
	public function addAttachment($command)
	{
		$data = [
			'id' => $this->generateUUID(),
			'task_id' => $command->taskId,
			'type' => $command->type,
			'data' => json_encode($command->data),
			'creator_id' => $command->creator
		];
		return $this->create($data);
	}

	/**
	 * @param  Object $command
	 * @return App\Attachment
	 */
	public function updateAttachment($command)
	{
		$attachments = $this->model->where('task_id', '=', $command->taskId)
								 ->lists('id')->toArray();
		$commandId = isset($command->attachment['id']) ? $command->attachment['id'] : NULL;
		if (!in_array($commandId, $attachments)) {
			return $this->addAttachment($command);
		}
	}
}
