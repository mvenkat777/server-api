<?php

namespace Platform\Dashboard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Activity\Models\ParentEntityNotification;

class NotificationFeedTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(ParentEntityNotification $notification)
	{
        return [
            "id" => $notification->id,
            "version" => $notification->version,
            "entityId" => $notification->entityId,
            "isRead" => isset($notification->isRead) ? $notification->isRead : true,
            "objectType" => $notification->objectType,
            "rules" => $notification->rules,
            "entity" => $notification->entity,
            "subEntity" => $notification->subEntity,
            "updatedAt" => $notification->updatedAt,
            "lastSeen" => $notification->lastSeen
        ];
	}

}
