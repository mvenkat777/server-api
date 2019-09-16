<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaAttachmentRepository;

class AddSampleCriteriaAttachmentCommandHandler implements CommandHandler
{
    /**
     * The sample criteria attachment repository
     * @var SampleCriteriaAttachmentRepository
     */
    private $attachment;

    /**
     * @param SampleCriteriaAttachmentRepository $attachment
     */
    public function __construct(SampleCriteriaAttachmentRepository $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Handles AddSampleCriteriaAttachmentCommand
     * @param  AddSampleCriteriaAttachmentCommand $command
     * @return string
     */
	public function handle($command)
	{
        return $this->attachment->addAttachment($command);
	}
}