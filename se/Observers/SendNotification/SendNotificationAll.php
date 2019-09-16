<?php
namespace Platform\Observers\SendNotification;

use App\ActivityModel\NotificationModel\NotifyTarget;

class SendNotificationAll{

	/**
     * @var array $check
     */
	public $check = [];

	/**
     * @return mixed
     */
	public function getAllData(){
		
		$digest = [];

		$getAllData = NotifyTarget::where('localDate','>','2015-11-23')->
									select('action','actionObject.email','actionObject.displayName',
										   'target.message')->get();
		foreach ($getAllData as $key => $value) {
			$check[$value->actionObject['email']]['displayName'] = $value->actionObject['displayName']; 
			if (!isset($check[$value->actionObject['email']]['digest'][$value->action])) {
				 $check[$value->actionObject['email']]['digest'][$value->action] = 0; 
			}
			$check[$value->actionObject['email']]['digest'][$value->action] +=  1; 
		}
		return $check;
	}
}