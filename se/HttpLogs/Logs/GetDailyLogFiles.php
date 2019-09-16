<?php

namespace Platform\HttpLogs\Logs;

use Platform\HttpLogs\Repositories\Contracts\LogRepository;
class GetDailyLogFiles{


	public $logRepository;

	public function __construct(LogRepository $logRepository){
		
		$this->logRepository = $logRepository;
	}

	public function readLogFile($command){
		$filePath = $command->filePath;
		$handle = fopen($filePath, "r");
		$i = 1;
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
		    	$pattern = '/^.*ALERT.INFO: /';
				$string = preg_replace($pattern, '', $line);
				$pattern = '/ \\[\\] \\[\\]\n.*$/';
				$string = preg_replace($pattern, '', $string);
				$string = json_decode( $string , true );
				if( $string === NULL )
			   	{
			   		die( '{"status":false,"msg":"The post_data parameter must be valid JSON"}' );
			   	}
			   	
			   	if($i != 'NULL'){
			   		$i++;
			   		$this->logRepository->saveInDatabase($string);
			   	}else{
			   		return;
			   	}
		    }
			fclose($handle);
		} else {
		    return 'Unable to Open File !!!';
		} 
	}

	public function readSysLogFile($command){
		$filePath = $command->syslogPath;
		$handle = fopen($filePath,"r");
		if ($handle) {
			dd("ghj");
		}
		dd($handle);
	}
}