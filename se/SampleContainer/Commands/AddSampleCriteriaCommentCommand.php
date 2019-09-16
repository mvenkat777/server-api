<?php

namespace Platform\SampleContainer\Commands;

use Illuminate\Support\Facades\Auth;

class AddSampleCriteriaCommentCommand
{
    /**
     * The sample criteria id
     * @var string
     */
    public $criteriaId;

    /**
     * The comment
     * @var json
     */
    public $comment;

    /**
     * The commenter's id
     * @var string
     */
    public $commenterId;

    /**
     * Construct the AddSampleCriteriaCommentCommand
     * @param array $data
     */
    public function __construct($data){
        $this->criteriaId = $data['criteriaId'];
        $this->comment = $data['comment'];
        $this->commenterId = Auth::user()->id;
    }
}