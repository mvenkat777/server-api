<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaAttachmentRepository;

class DeleteSampleCriteriaAttachmentCommandHandler implements CommandHandler
{
    /**
     * The sampleCriteriaAttachment repositiry
     * @var string
     */
    private $criteriaAttachment;

    /**
     * Construct the handler
     * @param SampleCriteriaAttachmentRepository $sample
     */
    public function __construct(SampleCriteriaAttachmentRepository $criteriaAttachment)
    {
        $this->criteriaAttachment = $criteriaAttachment;
    }

    /**
     * Handle the DeleteSampleCriteriaAttachmentCommand
     * @param  DeleteSampleCriteriaAttachmentCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $criteriaAttachment = $this->criteriaAttachment->getByCriteriaIdAndAttachmentId(
            $command->criteriaId,
            $command->attachmentId
        );

        if ($criteriaAttachment) {
            $this->isAuthor($criteriaAttachment, Auth::user());
            return $criteriaAttachment->delete();
        }
        throw new SeException("Attachment not found.", 404);
    }

    /**
     * Check if authenticated user is the uploader of the attachment
     * @param  Object  $criteriaAttachment
     * @param  Object  $user
     * @return boolean
     */
    public function isAuthor($criteriaAttachment, $user)
    {
        if ($criteriaAttachment->uploader_id == $user->id) {
            return true;
        }
        throw new SeException(
            "You must be the uploader of the file to delete it.",
            401
        );
    }
}