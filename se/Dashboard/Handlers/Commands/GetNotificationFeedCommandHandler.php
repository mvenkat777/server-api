<?php

namespace Platform\Dashboard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Activity\Models\UserNotification;
use Platform\App\Activity\Models\ParentEntityNotification as PlatformNotification;
use Platform\App\Activity\Models\ParentLineEntityNotification as PlatformLineNotification;
use Platform\App\Activity\Models\UserLineNotification;
use Platform\App\Activity\Models\SubEntityLineNotification;
use Platform\App\Activity\Models\SubEntityNotification;
use Carbon\Carbon;

class GetNotificationFeedCommandHandler implements CommandHandler 
{
	public function handle($command)
	{
            if($command->entity == 'all'){
    			$user = UserNotification::where('userEmail', $command->user->email)
    									->first();
                if($user)
                {
                    $content = [];
                    foreach ($user->object as $key => $value) {
                        $value['isRead'] = true;
                        $collection = SubEntityNotification::where('entityId', $value['entityId'])
                                        ->orderBy('createdAt', 'DESC')
                                        ->get();
                        foreach ($collection as $count => $val) {
                            if($value['lastSeen'] < $val->createdAt){
                                $value['isRead'] = false;
                            }
                        }
                        array_push($content, $value);
                    }
                    $user->object = $content;
                }
            } else {
                $user = UserLineNotification::where('userEmail', $command->user->email)
                                        ->first();
                if($user)
                {
                    $content = [];
                    foreach ($user->object as $key => $value) {
                        $value['isRead'] = true;
                        $collection = SubEntityLineNotification::where('entityId', $value['entityId'])
                                        ->orderBy('createdAt', 'DESC')
                                        ->get();
                        foreach ($collection as $count => $val) {
                            if($value['lastSeen'] < $val->createdAt){
                                $value['isRead'] = false;
                            }
                        }
                        array_push($content, $value);
                    }
                    $user->object = $content;
                }
            }
            if($user != NULL){
                $sortedEntities = $this->sortByTime($user->object);
                $data = $this->getEntityDetails($sortedEntities, $command->entity);
                return $data;
            } else {
                return [];
            }

	}

    /** 
     * Get entity details
     *
     * @param   Array   $entities
     * @return  Array
     */
    private function getEntityDetails($entities, $type)
    {
        foreach($entities as $key => $entity) {
            if($type == 'line'){
                $entityDetails = PlatformLineNotification::find($entity['entityId']);
                $entityDetails['lastSeen'] = $entity['lastSeen'];
                $entityDetails['isRead'] = $entity['isRead'];
                $entityDetails['updatedAt'] = $entity['updatedAt'];
            } else {
                $entityDetails = PlatformNotification::find($entity['entityId']);
                $entityDetails['lastSeen'] = $entity['lastSeen'];
                 $entityDetails['isRead'] = $entity['isRead'];
                $entityDetails['updatedAt'] = $entity['updatedAt'];
            }
            if($entityDetails) {
                $entities[$key] = $entityDetails;
            }
        }
        return $entities;
    }

	/**
	 * To sort an array on the basis of ascending order
	 * @param array collection
	 * @return mixed
	 */
	public function sortByTime($collection)
	{
		usort($collection, function($a, $b) { //Sort the array using a user defined function
            return $a['updatedAt'] > $b['updatedAt'] ? -1 : 1; //Compare the scores
        });
        return $collection;
	}
}
