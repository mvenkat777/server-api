<?php
namespace Platform\HttpLogs\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\HttpLogs\Repositories\Contracts\LogRepository;
use App\FrameworkLog;

class EloquentLogRepository extends Repository implements LogRepository 
{

	public function model(){
		return 'App\FrameworkLog';
	}

	public function saveInDatabase($data){
		FrameworkLog::create($data);
		return 'Inserted Successfully';
	}

	public function getAllLogs(){
		return $this->model->orderBy('created_at', 'desc')->get();
	}

	public function getLogsByUserId($command){
		return $this->model->where('request.requesterID', $command->userId)
						   ->where('createdAt','>=',$command->fromDate)
						   ->orderBy('created_at', 'desc')
						   ->get();
	}

	public function getLogsByCreatedDate($command){
		return $this->model->where('createdAt','=',$command->date)
						   ->orderBy('created_at', 'desc')
						   ->get();
	}

	public function getLogsByFromDateToTillDate($command){
		return $this->model->where('createdAt','>=',$command->fromDate)
							->where('createdAt','<=',$command->tillDate)
							->orderBy('created_at', 'desc')
							->get();
	}

	public function getLogsByRequestType($command){
		return $this->model->where('request.type',$command->requestType)
						   ->orderBy('created_at', 'desc')
						   ->get();
	}

	public function getLogsByResponseStatus($command){
		return $this->model->where('response.status',$command->responseStatus)
						   ->orderBy('created_at', 'desc')
						   ->get();
	}

}