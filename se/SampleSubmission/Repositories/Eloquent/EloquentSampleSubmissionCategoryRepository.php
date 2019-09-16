<?php

namespace Platform\SampleSubmission\Repositories\Eloquent;

use App\SampleSubmissionCategory;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionCategoryRepository;

class EloquentSampleSubmissionCategoryRepository extends Repository implements SampleSubmissionCategoryRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'App\SampleSubmissionCategory';
    }

    /**
     * Get all the categories for a sample
     * @param  $sampleId
     * @return mixed
     */
    public function getBySampleId($sampleId)
    {
        return $this->model->where('sample_submission_id', $sampleId)
                           ->get();
    }
    /**
     * Add a new category to sample submission
     * @param array $data
     * @return mixed
     */
    public function addCategory($sampleId, $data)
    {
        $data = [
            'sample_submission_id' => $sampleId,
            'name' => $data['name'],
            'content' => isset($data['content']) ? $data['content'] : '',
        ];

        $exists = $this->getBySampleIdAndName($sampleId, $data['name']);

        if ($exists) {
            $this->update($data, $exists['id']);
            return $this->find($exists['id']);
        }

        $data['id'] = $this->generateUUID();
        return $this->create($data);
    }

    /**
     * Get a category based on categoryId
     * @param  string $sampleId
     * @param  string $categoryId
     * @return mixed
     */
    public function getCategory($sampleId, $categoryId)
    {
        return $this->model->where('sample_submission_id', $sampleId)
                           ->where('id', $categoryId)
                           ->first();
    }

    /**
     * Get a sample category by sampleId and name
     * @param  string $sampleId
     * @param  string $name
     * @return mixed
     */
    public function getBySampleIdAndName($sampleId, $name)
    {
        return $this->model->where('sample_submission_id', $sampleId)
                           ->where('name', $name)
                           ->first();
    }

    /**
     * Update a category
     * @param  string $categoryId
     * @param  array $data
     * @return mixed
     */
    public function updateCategory($categoryId, array $data)
    {
        $category = $this->find($categoryId);

        if (!$category) {
            throw new SeException('Category with given id not found.', 404);
        }

        $category->content = $data['content'];
        $category->update();
        return $category;
    }
}
