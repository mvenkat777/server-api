<?php

namespace Platform\Dashboard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\App\Activity\Models\SubEntityLineNotification;

class NotificationLineFeedDetailsTransformer extends TransformerAbstract 
{

    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(SubEntityLineNotification $subEntity)
    {
        if((isset($subEntity['meta']['displayName']) && isset($subEntity['meta']['systemName'])) || isset($subEntity['displayName'])){
            return [
                "id" => $subEntity->id,
                "version" => $subEntity->version,
                "entityId" => $subEntity->entityId,
                "isRead" => isset($subEntity->isRead) ? $subEntity->isRead : false,
                "priority" => $subEntity->priority,
                "actor" => $subEntity->actor,
                "verb" => $subEntity->verb,
                "displayName" => isset($subEntity['meta']['displayName']) ? $subEntity['meta']['displayName'] : $subEntity['displayName'],
                "systemName" => isset($subEntity['meta']['systemName']) ? $subEntity['meta']['systemName'] : $subEntity['systemName'],
                "meta" => isset($subEntity->meta['meta']) ? $subEntity->meta['meta'] : $subEntity->meta,
                "links" => $subEntity->links,
                "createdAt" => $subEntity->createdAt,
                "updatedAt" => $subEntity->updated_at->toDateTimeString()
            ];
        }
        return [
            "id" => $subEntity->id,
            "version" => $subEntity->version,
            "entityId" => $subEntity->entityId,
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
