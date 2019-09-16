<?php

namespace Platform\Dashboard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Observers\Repositories\TaskActivityRepository;
use App\Task;
use Platform\BusinessRules\Repositories\BusinessRuleActionTimeRepository;
use App\ImmediateActionRule;

class GetNotificationCommandHandler implements CommandHandler 
{

	public function __construct()
	{

	}

	public function handle($command)
	{
        $data = (new BusinessRuleActionTimeRepository)->getNotification($command->user->email);
        if($data){
        	$data->object = array_reverse($data->object);
        	return $data->object;
        } else {
        	return [];
        }
	}

}
