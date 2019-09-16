<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleSubmission\Commands\AddSampleSubmissionCategoriesCommand;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCategoryRepository;
use Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer;

class SampleSubmissionCategoryController extends ApiController
{
    /**
     * @var SampleSubmissionCategoryRepository
     */
    protected $category;

    /**
     * @param SampleSubmissionCategoryRepository    $category
     * @param DefaultCommandBus                     $commandBus
     */
    public function __construct(
        SampleSubmissionCategoryRepository $category,
        DefaultCommandBus $commandBus
    ) {
        parent::__construct(new Manager());

        $this->category = $category;
        $this->commandBus = $commandBus;
    }

    /**
     * Add a new category to a sample submission
     * @param string  $sampleId
     * @param Request $request
     */
    public function store($sampleId, Request $request)
    {
        $categories = $this->commandBus->execute(new AddSampleSubmissionCategoriesCommand($sampleId, $request->all()));

        if ($categories) {
            return $this->setStatusCode(201)->respondWithCollection(
                $categories,
                new SampleSubmissionCategoryTransformer,
                'SampleSubmissionCategory'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to save the categories. Please try again.');
    }

    /**
     * Add a new category to a sample submission
     * @param string  $sampleId
     * @param Request $request
     */
    public function find($sampleId, $categoryId)
    {
        $category = $this->category->getCategory($sampleId, $categoryId);

        if ($category) {
            $category->comments = $category->comments;
            $category->attachments = $category->attachments;
            return $this->respondWithItem(
                $category,
                new SampleSubmissionCategoryTransformer,
                'SampleSubmissionCategory'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError('No category found with that id. Please try again.');
    }

    /**
     * Update a category
     * @param  string  $sampleId
     * @param  string  $categoryId
     * @param  Request $request
     * @return mixed
     */
    public function update($sampleId, $categoryId, Request $request)
    {
        $category = $this->category->updateCategory($categoryId, $request->all());

        if ($category) {
            $category->comments = $category->comments;
            $category->attachments = $category->attachments;
            return $this->respondWithItem(
                $category,
                new SampleSubmissionCategoryTransformer,
                'SampleSubmissionCategory'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to update the category. Please try again.');
    }
}
