<?php

namespace Platform\SampleContainer\Commands;

use Illuminate\Support\Facades\Auth;

class AddSampleCriteriaAttachmentCommand
{
    /**
     * The sample criteria id
     * @var string
     */
    public $criteriaId;

    /**
     * The uploaded file
     * @var json
     */
    public $file;

    /**
     * The uploader's id
     * @var string
     */
    public $uploaderId;

    /**
     * Construct the AddSampleCriteriaAttachmentCommand
     * @param array $data
     */
	public function __construct($data){
        $this->criteriaId = $data['criteriaId'];
        $this->file = $data['file'];
        $this->uploaderId = Auth::user()->id;
	}
}