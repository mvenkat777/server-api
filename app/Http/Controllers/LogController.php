<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\HttpLogs\Commands\GetAllLogsCommand;
use Platform\HttpLogs\Commands\GetLogsByUserIdCommand;
use Platform\HttpLogs\Commands\GetLogsByCreatedDateCommand;
use Platform\HttpLogs\Commands\GetLogsFromDateToTillDateCommand;
use Platform\HttpLogs\Commands\GetLogsByRequestTypeCommand;
use Platform\HttpLogs\Commands\GetLogsByResponseStatusCommand;
use App\Http\Controllers\ApiController;
class LogController extends ApiController
{
	protected $commandBus;

    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        
        parent::__construct(new Manager());
    }

	public function getAllLogs($i=1)
    {
    	return $this->commandBus->execute(new GetAllLogsCommand());
	    /*	$data = (\App\FrameworkLog::paginate()->toArray());
	    	$rows['page'] = $data['current_page']; 
	    	$rows['total'] = $data['total'];
	    	$rows['rows'] = [];

	    	foreach ($data['data'] as $cell) {
	    		$getData = (object) [
						'cell' => [
		    				$cell['_id'],
		    				$cell['request']['type'],
		    				$cell['request']['requesterID'],
		    				$cell['request']['ip'],
		    				$cell['request']['path'],
		    				$cell['createdAt'],
		    				$cell['response']['message'],
		    				$cell['response']['status']
						]
					];
				array_push($rows['rows'], $getData);
	    	}
	    	return $rows; */
    }

    public function getLogsByUserId($userId, $fromDate){
    	return $this->commandBus->execute(new GetLogsByUserIdCommand($userId, $fromDate));	
    }

    public function getLogsByCreatedDate($date){
    	return $this->commandBus->execute(new GetLogsByCreatedDateCommand($date));
    }

    public function getLogsFromDateToTillDate($fromDate, $tillDate){
    	return $this->commandBus->execute(new GetLogsFromDateToTillDateCommand($fromDate, $tillDate));
    }

    public function getLogsByRequestType($requestType){
    	return $this->commandBus->execute(new GetLogsByRequestTypeCommand($requestType));
    }

    public function getByResponseStatus($responseStatus){
    	return $this->commandBus->execute(new GetLogsByResponseStatusCommand($responseStatus));
    }
}
