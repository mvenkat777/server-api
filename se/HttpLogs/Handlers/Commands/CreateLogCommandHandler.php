<?php
namespace Platform\HttpLogs\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\HttpLogs\Logs\GetDailyLogFiles;
use Platform\HttpLogs\Repositories\Contracts\LogRepository;

class CreateLogCommandHandler implements CommandHandler 
{
	public $getLog;

	public $logRepository;

	public function __construct(GetDailyLogFiles $getLog, LogRepository $logRepository){
		
		$this->getLog = $getLog;
		$this->logRepository = $logRepository;
	}

	public function handle($command)
	{
		return $this->getLog->readLogFile($command);
	}

}