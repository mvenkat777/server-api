<?php
namespace Platform\HttpLogs\Logs;

use Carbon\Carbon;
class IpLogs{

	public function getFailedLoginRequestIp(){
		$logFile = storage_path()."/logs/LoggerInfo/logs/api-http-".Carbon::now()->format('Y-m-d').".log";
		$file=file_get_contents($logFile);
		$lines=explode("\n",$file);
		$accepted=array();
		$fail=array();
		$r="/(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/";
		$search = '"status_code\":401';
		for($i=0;$i < count($lines);$i++){
		  $t=array();
		  if(preg_match($r,$lines[$i],$t)){
		  	$ip=$t[0];
		  }
		  if(strpos($lines[$i], $search)){
		    $fail[]=$ip;
		  }
		  else{
		    $accepted[]=$ip;
		  }
		}
		return [
                    'Successfull' => $accepted,
                    'Failed' => $fail
                ];
	}
}