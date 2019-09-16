<?php 
namespace Platform\HttpLogs\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\HttpLogs\Repositories\Contracts\LogRepository;
class GetLogsByRequestTypeCommandHandler{
	
	protected $logRepository;

	function __construct(LogRepository $logRepository)
	{
		$this->logRepository = $logRepository;
	}

	/**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
    	return $this->logRepository->getLogsByRequestType($command);
    }
}