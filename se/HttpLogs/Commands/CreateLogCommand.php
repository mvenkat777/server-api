<?php
namespace Platform\HttpLogs\Commands;

class CreateLogCommand 
{
	public $filePath;

	public $syslogPath;

	public function __construct($filePath, $syslogPath){
		$this->filePath = $filePath;
		$this->syslogPath = $syslogPath;
	}

}