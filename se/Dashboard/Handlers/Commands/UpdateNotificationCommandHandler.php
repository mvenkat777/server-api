<?php

namespace Platform\Dashboard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\BusinessRules\Repositories\BusinessRuleActionTimeRepository;

class UpdateNotificationCommandHandler implements CommandHandler 
{

	public function handle($command)
	{
                $update = (new BusinessRuleActionTimeRepository)->updateNotification($command->user->email, $command->notificationId);
                if($update)
                {
                	return (new BusinessRuleActionTimeRepository)->getNotification($command->user->email);
                } else {
                	throw new SeException("Failed to update seen", 422, '90001422');
                }
	}

}
