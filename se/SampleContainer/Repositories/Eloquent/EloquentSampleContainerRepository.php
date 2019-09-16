<?php

namespace Platform\SampleContainer\Repositories\Eloquent;

use App\SampleContainer;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;

class EloquentSampleContainerRepository extends Repository implements SampleContainerRepository
{
    /**
     * Define the Sample Container model
     *
     * @return string
     */
	public function model(){
		return 'App\SampleContainer';
	}

    /**
     * Add a new Sample Container
     *
     * @param array $data
     * @return mixed
     */
    public function addSampleContainer(array $data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'techpack_id' => $data['techpackId'],
            'flat_image' => $data['flatImage'],
            'customer_id' => $data['customerId'],
            'style_code' => $data['styleCode'],
        ];
        $sampleContainer = $this->model->create($data);

        return $this->getByIdWithRelations($sampleContainer->id);
    }

    /**
     * Get a Sample Container by id with its relations
     * @param  string $sampleContainerId
     * @return mixed
     */
    public function getByIdWithRelations($sampleContainerId)
    {
        return $this->model->with([
            'techpack',
            'customer',
            'style',
            'samples' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])->where('id', $sampleContainerId)
               ->first();
    }

    /**
     * Get a sample container by techpack id
     * @param  string $techpackId
     * @return mixed
     */
    public function getByTechpackId($techpackId)
    {
        return $this->model->where('techpack_id', $techpackId)->first();
    }

    /**
     * @param  array $request
     * @return mixed
     */
    public function filterSampleContainer($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }

    /**
     * Delete Sample Container
     * @param  string $id 
     * @return boolean     
     */
    public function deleteContainer($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Archive Sample Container
     * @param  string $id 
     * @return boolean     
     */
    public function archiveContainer($id)
    {
        $container = $this->model->where('id', $id)->first();
        $archived = $container->update(['archived_at' => Carbon::now()]);
        if ($archived && !empty($container->samples)) {
            foreach ($container->samples as $sample) {
                \DB::table('samples')->where('id', $sample->id)
                    ->update(['archived_at' => Carbon::now()]);
            }
        } 
        return $archived;   
    }

    /**
     * Complete a sample container
     * @param  string $id 
     * @return array     
     */
    public function completeSampleContainer($id)
    {
        $container = $this->model->where('id', $id)->first();
        $completed = $container->update(['completed_at' => Carbon::now()]);
        if ($completed && !empty($container->samples)) {
            foreach ($container->samples as $sample) {
                \DB::table('samples')->where('id', $sample->id)
                    ->update(['completed_at' => Carbon::now()]);
            }
        } 
        return $completed; 
    }

    /**
     * Complete a sample container
     * @param  string $id 
     * @return array     
     */
    public function undoSampleContainer($id)
    {
        $container = $this->model->where('id', $id)->first();
        $undo = $container->update(['completed_at' => NULL]);
        if ($undo && !empty($container->samples)) {
            foreach ($container->samples as $sample) {
                \DB::table('samples')->where('id', $sample->id)
                    ->update(['completed_at' => NULL]);
            }
        } 
        return $undo; 
    }

    /**
     * Rollback Sample Container
     * @param  string $id 
     * @return boolean     
     */
    public function rollbackContainer($id)
    {
        $container = $this->model->where('id', $id)->first();
        $rollback = $container->update(['archived_at' => NULL]);
        if ($rollback && !empty($container->samples)) {
            foreach ($container->samples as $sample) {
                \DB::table('samples')->where('id', $sample->id)
                    ->update(['archived_at' => NULL]);
            }
        }   
        return $rollback;
    }
}