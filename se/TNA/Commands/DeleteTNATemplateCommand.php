<?php

namespace Platform\TNA\Commands;

class DeleteTNATemplateCommand 
{
    public $templateId;

	public function __construct($templateId){
        $this->templateId = $templateId;
	}

}
