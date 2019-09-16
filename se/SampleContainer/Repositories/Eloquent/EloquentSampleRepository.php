<?php

namespace Platform\SampleContainer\Repositories\Eloquent;

use App\Sample;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;

class EloquentSampleRepository extends Repository implements SampleRepository
{
    /**
     * The sample model namespace
     * @return string
     */
	public function model(){
		return 'App\Sample';
	}

    /**
     * Persist the sample data
     * @param AddNewSampleCommand $command
     */
    public function addSample($command)
    {
        $data = [
            'id' => $this->generateUUID(),
            'sample_container_id' => $command->sampleContainerId,
            'title' => $command->title,
            'type' => $command->type,
            'author_id' => $command->authorId,
            'image' => $command->image,
            'sent_date' => $command->sentDate,
            'received_date' => $command->receivedDate,
            'vendor_id' => $command->vendorId,
            'weight_or_quality' => $command->weightOrQuality,
            'fabric_or_content' => $command->fabricOrContent,
            'pom' => $command->pom,
            'action_forward' => $command->actionForward,
        ];

        $sample = $this->create($data);
        return $this->getByIdWithRelations($sample->id);
    }

    /**
     * Update a sample
     * @param  UpdateSampleCommand $command
     * @return mixed
     */
    public function updateSample($command)
    {
        $sample = $this->getBySampleContainerIdAndSampleId(
            $command->sampleContainerId,
            $command->sampleId
        );

        if ($sample) {
            $data = [
                'title' => $command->title,
                'type' => $command->type,
                'author_id' => $command->authorId,
                'image' => $command->image,
                'sent_date' => $command->sentDate,
                'received_date' => $command->receivedDate,
                'vendor_id' => $command->vendorId,
                'weight_or_quality' => $command->weightOrQuality,
                'fabric_or_content' => $command->fabricOrContent,
                'pom' => $command->pom,
                'action_forward' => $command->actionForward,
            ];

            $sample->update($data);
            return $this->getByIdWithRelations($sample->id);
        }
        throw new SeException("Sample not found in this container.", 404);
    }

    /**
     * Get a sample by container id and sample id
     * @param  string $sampleContainerId
     * @param  string $sampleId
     * @return mixed
     */
    public function getBySampleContainerIdAndSampleId($sampleContainerId, $sampleId) {
        return $this->model->where('sample_container_id', $sampleContainerId)
                           ->where('id', $sampleId)
                           ->first();
    }

    /**
     * Get a Sample by id with its relations
     * @param  string $sampleId
     * @return mixed
     */
    public function getByIdWithRelations($sampleId)
    {
        return $this->model->with([
            'sampleContainer', 'author', 'vendor', 'criterias' => function ($query) {
                $query->with('attachments');
            }
        ])->where('id', $sampleId)->first();
    }

    /**
     * Complete sample
     * @param  string $containerId 
     * @param  string $sampleId    
     * @return boolean              
     */
    public function completeSample($containerId, $sampleId)
    {
        $sample = $this->model->where('id', $sampleId)->first();
        return $sample->update(['completed_at' => Carbon::now()]);  
    }

    /**
     * Undo Style
     * @param  string $containerId 
     * @param  string $sampleId    
     * @return boolean              
     */
    public function undoSample($containerId, $sampleId)
    {
        $sample = $this->model->where('id', $sampleId)->first();
        return $sample->update(['completed_at' => NULL]);
    }
    /**
     * Archive Sample 
     * @param  string $id 
     * @return boolean     
     */
    public function archiveSample($id)
    {
        $sample = $this->model->where('id', $id)->first();
        return $sample->update(['archived_at' => Carbon::now()]);  
    }

    /**
     * Rollback Sample
     * @param  string $id 
     * @return boolean     
     */
    public function rollbackSample($id)
    {
        $sample = $this->model->where('id', $id)->first();
        return $sample->update(['archived_at' => NULL]);
    }

    /**
     * Get All Samples created today 
     * This method is getting called for sending digest notification
     * @param  string $id 
     * @return mixed   
     */
    public function getTodayCreatedSampleList($id)
    {
        return $this->model->where('created_at','>', Carbon::today())
                            ->where('author_id', $id)
                            ->whereNULL('archived_at')
                            ->whereNULL('deleted_at')
                            ->get()
                            ->toArray();
    }
}
