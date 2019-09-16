<?php

namespace Platform\SampleSubmission\Repositories\Eloquent;

use App\SampleSubmission;
use Illuminate\Support\Facades\Auth;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\SampleSubmission\Repositories\Contracts\SampleSubmissionRepository;

class EloquentSampleSubmissionRepository extends Repository implements SampleSubmissionRepository
{

    public function model()
    {
        return 'App\SampleSubmission';
    }

    /**
     * Get all the sample submissions
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get meta fields of all the sample submissions
     * @return mixed
     */
    public function getMeta($item = 100)
    {

        return $this->model->select(
            [
                'id', 'name', 'user_id', 'techpack_id', 'style_code', 'sent_date', 'received_date', 'vendor', 'customer_id', 'type',
            ]
        )->paginate($item);
    }

    /**
     * Submit a new sample
     * @param  array $data
     * @return mixed
     */
    public function submitSample($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'name' => $data['name'],
            'user_id' => Auth::user()->id,
            'techpack_id' => $data['techpack'],
            'style_code' => $data['styleCode'],
            'sent_date' => $data['sentDate'] != '' ? \Carbon\Carbon::parse($data['sentDate'])->toDateString() : null,
            'received_date' => $data['receivedDate'] != '' ? \Carbon\Carbon::parse($data['receivedDate'])->toDateString() : null,
            'type' => $data['type'],
            'content' => $data['content'],
            'weight' => $data['weight'],
            'vendor' => $data['vendor'],
            'customer_id' => $data['customer'],
        ];

        return $this->create($data);
    }

    /**
     * Update a sample
     * @param  array $data
     * @param  string $sampleId
     * @return mixed
     */
    public function updateSample($data, $sampleId)
    {
        $sample = $this->find($sampleId);
        if ($sample->name != $data['name']) {
            $sample = $this->model->where('name', $data['name'])->first();
            if ($sample) {
                throw new SeException('A sample with that name already exists.', 422);
            }
        }

        $data = [
            'name' => $data['name'],
            'techpack_id' => $data['techpack'],
            'style_code' => $data['styleCode'],
            'sent_date' => $data['sentDate'] != '' ? \Carbon\Carbon::parse($data['sentDate'])->toDateString() : null,
            'received_date' => $data['receivedDate'] != '' ? \Carbon\Carbon::parse($data['receivedDate'])->toDateString() : null,
            'type' => $data['type'],
            'content' => $data['content'],
            'weight' => $data['weight'],
            'vendor' => $data['vendor'],
            'customer_id' => $data['customer'],
        ];

        $updated = $this->update($data, $sampleId);

        if ($updated) {
            return $this->find($sampleId);
        }

        return false;
    }

    /**
     * Get sample submissions based on user id
     * @param  string $userId
     * @return mixed
     */
    public function getByUserId($userId)
    {
        return $this->model->where('user_id', $userId)
                               ->get();
    }

    /**
     * Delete a sample submission
     * @param  string $sampleId
     * @return mixed
     */
    public function deleteSample($sampleId)
    {
        $sample = $this->find($sampleId);

        if (!$sample) {
            throw new SeException('A sample submission with that id not found.', 404);
        }
        return $sample->delete();
    }

    /**
     * Filter SampleSubmissions
     * @param  array $request
     * @return mixed
     */
    public function filterSamples($request)
    {
        $item = isset($request['item']) ? $request['item'] : 100;
        return $this->filter($request)->paginate($item);
    }

    /**
     * Get sample by name
     * @param  string $name
     * @return  App\SampleSubmission
     */
    public function getSampleByName($name)
    {
      return $this->model->where('name', 'ILIKE', $name)->first();
    }
}
