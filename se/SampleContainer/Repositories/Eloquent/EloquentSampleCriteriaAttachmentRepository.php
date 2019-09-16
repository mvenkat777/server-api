<?php

namespace Platform\SampleContainer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaAttachmentRepository;
use App\SampleCriteriaAttachment;

class EloquentSampleCriteriaAttachmentRepository extends Repository implements SampleCriteriaAttachmentRepository
{
    /**
     * Get the SampleCriteriaAttachment model
     * @return string
     */
	public function model(){
		return 'App\SampleCriteriaAttachment';
	}

    /**
     * Persists the attachment
     * @param AddSampleCriteriaAttachmentCommand $command
     */
    public function addAttachment($command)
    {
        $data = [
            'id' => $this->generateUUID(),
            'sample_criteria_id' => $command->criteriaId,
            'file' => $command->file,
            'uploader_id' => $command->uploaderId,
        ];

        $attachment = $this->create($data);
        return $this->getByIdWithRelations($attachment->id);
    }

    /**
     * Get an attachment by criteriaId and attachmentId
     * @param AddSampleCriteriaAttachmentCommand $command
     */
    public function getByCriteriaIdAndAttachmentId($criteriaId, $attachmentId)
    {
        return $this->model->where('sample_criteria_id', $criteriaId)
                           ->where('id', $attachmentId)
                           ->first();
    }

    /**
     * Get an attachment by id with its relations
     * @param  string $attachmentId
     * @return mixed
     */
    public function getByIdWithRelations($attachmentId)
    {
        return $this->model->with(['uploader'])
                           ->where('id', $attachmentId)
                           ->first();
    }

}