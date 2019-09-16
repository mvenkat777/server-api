<?php

namespace Platform\Dashboard\Commands;

class GetNotificationFeedByEntityCommand 
{
    /**
     * @var String
     */
    public $entityId;

    public $entity;

	public function __construct($entityId, $entityName){
        $this->entityId = $entityId;
        $this->entity = $entityName;
	}

}
