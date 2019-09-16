<?php

namespace Platform\Dashboard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Activity\Models\SubEntityNotification;
use Platform\App\Activity\Models\SubEntityLineNotification;
use Platform\App\Activity\Models\UserNotification;
use Platform\App\Activity\Models\UserLineNotification;
use Carbon\Carbon;

class GetNotificationFeedByEntityCommandHandler implements CommandHandler 
{

	public function handle($command)
	{
        $currentTime =  Carbon::now()->toDateTimeString();
        if($command->entity == 'all'){
            UserNotification::where('userEmail', \Auth::user()->email)
                            ->where('object.entityId', $command->entityId)
                            ->update([
                                'object.$.lastSeen' => $currentTime,
                             ]);
            $collection = SubEntityNotification::where('entityId', $command->entityId)
                ->orderBy('createdAt', 'DESC')
                ->limit(10)
                ->get(); 
            foreach ($collection as $key => $value) {
                if($currentTime > $value->createdAt){
                    $value->isRead = true;
                } else{
                    $value->isRead = false;
                }
            }
            return $collection;
        } else {
            UserLineNotification::where('userEmail', \Auth::user()->email)
                            ->where('object.entityId', $command->entityId)
                            ->update([
                                'object.$.lastSeen' => $currentTime,
                             ]);
            $collection = SubEntityLineNotification::where('entityId', $command->entityId)
                ->orderBy('createdAt', 'DESC')
                ->limit(10)
                ->get();
            foreach ($collection as $key => $value) {
                if($currentTime > $value->createdAt){
                    $value->isRead = true;
                } else{
                    $value->isRead = false;
                }
            }
            return $collection;
        }
	}

}
