<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddSampleCriteriaAttachmentCommand;
use Platform\SampleContainer\Commands\DeleteSampleCriteriaAttachmentCommand;
use Platform\SampleContainer\Transformers\SampleCriteriaAttachmentTransformer;
use Platform\SampleContainer\Validators\SampleCriteriaAttachmentValidator;

class SampleCriteriaAttachmentController extends ApiController
{
    /**
     * The command bus
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * The SampleCriteriaAttachmentValidator
     * @var SampleCriteriaAttachmentValidator
     */
    private $validator;

    /**
     * Construct the controller
     * @param DefaultCommandBus                 $commandBus
     * @param SampleCriteriaAttachmentValidator $validator
     */
    public function __construct(
        DefaultCommandBus $commandBus,
        SampleCriteriaAttachmentValidator $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;

        parent::__construct(new Manager());
    }

    /**
     * Add an attachment to a sample criteria
     * @param  string  $criteriaId
     * @param  Request $request
     * @return mixed
     */
    public function store($criteriaId, Request $request)
    {
        $data = $request->all();
        $data['criteriaId'] = $criteriaId;
        $this->validator->setCreationRules()->validate($data);

        $attachment = $this->commandBus->execute(
            new AddSampleCriteriaAttachmentCommand($data)
        );

        if ($attachment) {
            return $this->respondWithNewItem(
                $attachment,
                new SampleCriteriaAttachmentTransformer,
                'sampleCriteriaAttachment'
            );
        }
        return $this->respondError('Failed to attach the file');
    }

    /**
     * Delete a sample criteria attachment
     * @param  string $criteriaId
     * @param  string $attachmentId
     * @return string
     */
    public function destroy($criteriaId, $attachmentId)
    {
        $deleted = $this->commandBus->execute(
            new DeleteSampleCriteriaAttachmentCommand($criteriaId, $attachmentId)
        );

        if ($deleted) {
            return $this->respondOk("Attachment removed successfully.");
        }
        return $this->respondError('Failed to delete the attachment');
    }
}
