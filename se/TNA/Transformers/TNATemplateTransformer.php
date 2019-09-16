<?php

namespace Platform\TNA\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;

class TNATemplateTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($tnaTemplate)
	{
        $data = json_decode($tnaTemplate->data);
        $data->templateId = $tnaTemplate->id;
        $data->creator = (new MetaUserTransformer)->transform($tnaTemplate->creator);
        $data->count = $tnaTemplate->count;
        return $data;
	}

}
