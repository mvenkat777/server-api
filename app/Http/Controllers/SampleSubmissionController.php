<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleSubmission\Commands\SubmitSampleCommand;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCategoryRepository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionRepository;
use Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer;
use Platform\SampleSubmission\Transformers\SampleSubmissionTransformer;
use Platform\SampleSubmission\Validators\SampleSubmissionValidator;
use Platform\Observers\Commands\GetSampleActivityDetailsCommand;

class SampleSubmissionController extends ApiController
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
     * @var Platform\SampleSubmission\Validators\SampleSubmissionValidator
     */
    protected $validator;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @param SampleSubmissionRepository            $sample
     * @param SampleSubmissionCategoryRepository    $category
     * @param SampleSubmissionValidator             $validator
     * @param DefaultCommandBus                     $commandBus
     */
    public function __construct(
        SampleSubmissionRepository $sample,
        SampleSubmissionCategoryRepository $category,
        SampleSubmissionValidator $validator,
        DefaultCommandBus $commandBus
    ) {
        parent::__construct(new Manager());

        $this->sample = $sample;
        $this->category = $category;
        $this->validator = $validator;
        $this->commandBus = $commandBus;
    }

    /**
     * Get all sample submissions for logged in user
     * @return mixed
     */
    public function index(Request $request)
    {
        $samples = $this->sample->getMeta($request->item);

        if ($samples) {
            return $this->respondWithPaginatedCollection($samples, new MetaSampleSubmissionTransformer, 'SampleSubmission');
        }
        return $this->setStatusCode(404)
                    ->respondWithError('No sample submissions found for this user.');
    }

    /**
     * Create a new Sample submission
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $this->validator->setSampleSubmissionRules()->validate($data);

        $sample = $this->commandBus->execute(new SubmitSampleCommand($data));
        if ($sample) {
            return $this->respondWithNewItem($sample, new SampleSubmissionTransformer, 'SampleSubmission');
        }
        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to submit the new sample. Please try agaian.');
    }

    /**
     * Get a sample submission based on id
     * @param  string $sampleId
     * @return miced
     */
    public function find($sampleId)
    {
        $sample = $this->sample->find($sampleId);

        if ($sample) {
            return $this->respondWithItem($sample, new SampleSubmissionTransformer, 'SampleSubmission');
        }
        return $this->setStatusCode(404)
                    ->respondWithError('No sample submissions found with this id.');
    }

    /**
     * Delete a sample submission
     * @param  string $sampleId
     * @return mixed
     */
    public function destroy($sampleId)
    {
        $deleted = $this->sample->deleteSample($sampleId);

        if ($deleted) {
            return $this->respondOk('Sample deleted successfully.');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Something went wrong. We were not able to dlete the sample submission.');
    }

    /**
     * Update a sample
     * @param  string  $sampleId
     * @param  Request $request
     * @return mixed
     */
    public function update($sampleId, Request $request)
    {
        $data = $request->all();

        $this->validator->setSampleUpdationRules()->validate($data);

        $sample = $this->sample->updateSample($data, $sampleId);

        if ($sample) {
            return $this->respondWithItem($sample, new SampleSubmissionTransformer, 'SampleSubmission');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to update the sample. Please try agaian.');
    }

    /**
     * filter Sample Submissions
     * @param  Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        $samples = $this->sample->filterSamples($request->all());
        if ($samples) {
            return $this->respondWithPaginatedCollection($samples, new MetaSampleSubmissionTransformer, 'SampleSubmission');
        }
        return $this->setStatusCode(404)
                    ->respondWithError('No sample submissions found.');

    }

    /**
     * get sample Activity Log
     * 
     * @param  Request $request
     * @return mixed
     */
    public function getSampleActivity($taskId){
        $sample = $this->commandBus->execute(new GetSampleActivityDetailsCommand($taskId));
        return $this->respondWithArray(['data' => $sample]);
    }
}
