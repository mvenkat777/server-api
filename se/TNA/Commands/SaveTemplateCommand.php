<?php

namespace Platform\TNA\Commands;

class SaveTemplateCommand 
{
    public $title;

    public $description;

    public $data;

    public $creator_id;

    public $isMilestoneTemplate;

    public $count;

	public function __construct($data){
        $this->title = $data['title'];
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->creator_id = isset($data['creator_id']) ? $data['creator_id'] : \Auth::user()->id;
        $this->isMilestoneTemplate = isset($data['isMilestoneTemplate']) 
                                        ? $data['isMilestoneTemplate']
                                        : true;
        $this->data = $data['data'];
        $this->count = isset($data['count']) ? $data['count'] : 0;
	}

}
