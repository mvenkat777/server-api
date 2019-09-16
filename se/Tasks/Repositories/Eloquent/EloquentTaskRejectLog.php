<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Providers\ConversionProvider;
use Platform\Tasks\Repositories\Contracts\TaskRejectLog;

class EloquentTaskRejectLog extends Repository implements TaskRejectLog{

	public function model(){
		return 'App\TaskRejectLog';
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTaskById($id){
		$task = \DB::table('tasks')->where('id', $id)
				->first();

		return $task;
	}

	

	public function insertAssigneeStatusLog($command){
		$data = [
			'id' => $this->generateUUID(),
			'task_id' => $command->taskId,
			'creator_id' => $this->getTaskById($command->taskId)->creator_id,
			'assignee_id' => $this->getTaskById($command->taskId)->assignee_id,
			'reason' => $command->note
		];
		try {
            \DB::beginTransaction();
			$task = $this->create($data);
			\DB::commit();
			return $task;
        } catch (\Exception $e) {
            throw new SeException('Failed to save task log.', 500);
        }
	}

}
