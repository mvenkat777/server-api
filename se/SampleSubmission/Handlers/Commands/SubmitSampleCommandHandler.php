<?php

namespace Platform\SampleSubmission\Handlers\Commands;

use Illuminate\Support\Facades\DB;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\SampleSubmission\Commands\AddSampleSubmissionCategoriesCommand;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionAttachmentRepository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCategoryRepository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionRepository;

class SubmitSampleCommandHandler implements CommandHandler
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
     * @param DefaultCommandBus                     $commandBus
     */
    public function __construct(
        SampleSubmissionRepository $sample,
        SampleSubmissionCategoryRepository $category,
        SampleSubmissionAttachmentRepository $attachment,
        DefaultCommandBus $commandBus
    ) {
        $this->sample = $sample;
        $this->category = $category;
        $this->attachment = $attachment;
        $this->commandBus = $commandBus;
    }

    public function handle($command)
    {
        try {
            DB::beginTransaction();
            $isSample = $this->sample->getSampleByName($command->data['name']);
            if ($isSample) {
                throw new SeException("Sample Name already present", 422);
            }
            $sample = $this->sample->submitSample($command->data);
            $this->commandBus->execute(new AddSampleSubmissionCategoriesCommand($sample->id, $command->categories));
            DB::commit();

            return $sample;
        } catch (Exception $e) {
            DB::rollback();
            throw new SeException('Something went wrong. We were not able to submit the sample. Please try again.');
        }
    }
}
