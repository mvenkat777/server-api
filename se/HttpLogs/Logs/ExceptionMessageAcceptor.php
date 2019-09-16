<?php
namespace Platform\HttpLogs\Logs;
use Platform\HttpLogs\Logs\HttpLog;

class ExceptionMessageAcceptor extends HttpLog{

	public function getExceptionMessage($request, $e){
		$this->logLineData($this->getOnlyRequestExceptionData($request, $e)); 
	}
}