<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddSampleCriteriaCommentCommand;
use Platform\SampleContainer\Commands\DeleteSampleCriteriaCommentCommand;
use Platform\SampleContainer\Transformers\SampleCriteriaCommentTransformer;
use Platform\SampleContainer\Validators\SampleCriteriaCommentValidator;

class SampleCriteriaCommentController extends ApiController
{
    /**
     * The command bus
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * The SampleCriteriaCommentValidator
     * @var SampleCriteriaCommentValidator
     */
    private $validator;

    /**
     * Construct the controller
     * @param DefaultCommandBus              $commandBus
     * @param SampleCriteriaCommentValidator $validator
     */
    public function __construct(
        DefaultCommandBus $commandBus,
        SampleCriteriaCommentValidator $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;

        parent::__construct(new Manager());
    }

    /**
     * Add a comment to a sample criteria
     * @param  string  $criteriaId
     * @param  Request $request
     * @return mixed
     */
    public function store($criteriaId, Request $request)
    {
        $data = $request->all();
        $data['criteriaId'] = $criteriaId;
        $this->validator->setCreationRules()->validate($data);

        $comment = $this->commandBus->execute(
            new AddSampleCriteriaCommentCommand($data)
        );

        if ($comment) {
            return $this->respondWithNewItem(
                $comment,
                new SampleCriteriaCommentTransformer,
                'sampleCriteriaComment'
            );
        }
        return $this->respondError('Failed to add the comment. Please try again.');
    }

    /**
     * Delete a sample criteria comment
     * @param  string $criteriaId
     * @param  string $commentId
     * @return string
     */
    public function destroy($criteriaId, $commentId)
    {
        $deleted = $this->commandBus->execute(
            new DeleteSampleCriteriaCommentCommand($criteriaId, $commentId)
        );

        if ($deleted) {
            return $this->respondOk("Comment removed successfully.");
        }
        return $this->respondError('Failed to delete the commnet');
    }
}
