<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaCommentRepository;

class AddSampleCriteriaCommentCommandHandler implements CommandHandler
{
    /**
     * The sample criteria comment repository
     * @var SampleCriteriaCommentRepository
     */
    private $comment;

    /**
     * @param SampleCriteriaCommentRepository $comment
     */
    public function __construct(SampleCriteriaCommentRepository $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Handles AddSampleCriteriaCommentCommand
     * @param  AddSampleCriteriaCommentCommand $command
     * @return string
     */
    public function handle($command)
    {
        return $this->comment->addComment($command);
    }
}