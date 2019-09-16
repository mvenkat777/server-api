<?php

namespace Platform\SampleSubmission\Commands;

class AddSampleSubmissionCategoriesCommand
{
	public $categories;
	public $sampleId;

	public function __construct($sampleId, $categories)
	{
		$this->sampleId  = $sampleId;
		$this->categories  = $categories;
	}

}
