<?php

namespace Platform\Dashboard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Activity\Models\SubEntityNotification;

class NotificationFeedDetailsTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(SubEntityNotification $subEntity)
	{
        return [
            "id" => $subEntity->id,
            "version" => $subEntity->version,
            "entityId" => $subEntity->entityId,
            "isRead" => isset($subEntity->isRead)?$subEntity->isRead:false,
            "priority" => $subEntity->priority,
            "actor" => $subEntity->actor,
            "verb" => $subEntity->verb,
            "meta" => $subEntity->meta,
            "links" => $subEntity->links,
            "createdAt" => $subEntity->createdAt,
            "updatedAt" => $subEntity->updated_at->toDateTimeString()
        ];
	}

}
