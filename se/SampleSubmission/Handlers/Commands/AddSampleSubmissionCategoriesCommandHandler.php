<?php

namespace Platform\SampleSubmission\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCategoryRepository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionRepository;

class AddSampleSubmissionCategoriesCommandHandler implements CommandHandler
{
	/**
     * @var SampleSubmissionRepository
     */
    protected $sample;

    /**
     * @var SampleSubmissionCategoryRepository
     */
    protected $category;

    /**
     * @var Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository
     */
    protected $attachment;

    /**
     * @param SampleSubmissionRepository            $sample
     * @param SampleSubmissionCategoryRepository    $category
     * @param SampleSubmissionAttachmentRepository  $attachment
     */
    public function __construct(
        SampleSubmissionRepository $sample,
        SampleSubmissionCategoryRepository $category,
        SampleSubmissionAttachmentRepository $attachment
    ) {
        $this->sample = $sample;
        $this->category = $category;
        $this->attachment = $attachment;
    }

    /**
     * Handles AddSampleSubmissionCategoriesCommand
     * @param  AddSampleSubmissionCategoriesCommand $command
     * @return
     */
	public function handle($command)
	{
		$sampleId = $command->sampleId;

		foreach ($command->categories as $category) {
		    $newCategory = $this->category->addCategory($sampleId, $category);
		    if (isset($category['attachments'])) {
		        foreach ($category['attachments'] as $attachment) {
		            $this->attachment->addAttachment($sampleId, $newCategory->id, $attachment);
		        }
		    }
		}

		return $this->category->getBySampleId($sampleId);
	}

}
