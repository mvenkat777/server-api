<?php

namespace Platform\Line\Repositories\Eloquent;

use App\Line;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Line\Repositories\Contracts\LineRepository;

class EloquentLineRepository extends Repository implements LineRepository
{

	/**
	 * Return the model name
	 *
	 * @return string
	 */
	public function model(){
		return 'App\Line';
	}

	/**
	 * Get a line item with styles based on lineId
	 *
	 * @param string $lineId
	 * @return mixed
	 */
	public function getById($lineId) {
            return $this->model
                        ->with([
                            'customer',
                            'productionLead',
                            'salesRepresentative',
                            'productDevelopmentLead',
                            'merchandiser',
                            'styles' => function ($query) {
                                $query->with(['techpack', 'tna', 'sampleSubmissions', 'order']);
                                $query->orderBy('updated_at', 'desc');
                            }
                        ])
                        ->find($lineId);
	}

	/**
	 * Creates a new line item
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function createNewLine($data) {
            $data = [
                'id' => $this->generateUUID(),
                'code' => $data['code'],
                'name' => $data['name'],
                'customer_id' => $data['customerId'],
                'sales_representative_id' => $data['salesRepId'],
                'production_lead_id' => $data['productionLeadId'],
                'product_development_lead_id' => $data['productDevelopmentLeadId'],
                'merchandiser_id' => $data['merchandiserId'],
                'so_target_date' => $data['soTargetDate'],
                'delivery_target_date' => $data['deliveryTargetDate'],
                'targetCustomer' => $data['targetCustomer'],
                'fitReference' => $data['fitReference'],
                'category' => $data['category'],
                'styles_count' => intval($data['stylesCount']),
                'vlp_attachments' => isset($data['vlpAttachments']) ? $data['vlpAttachments'] : null,
            ];

            return $this->create($data);
	}

	public function updateLine($lineId, $data) {
            $data = [
                'name' => $data['name'],
                'customer_id' => $data['customerId'],
                'sales_representative_id' => $data['salesRepId'],
                'production_lead_id' => $data['productionLeadId'],
                'product_development_lead_id' => $data['productDevelopmentLeadId'],
                'merchandiser_id' => $data['merchandiserId'],
                'so_target_date' => $data['soTargetDate'],
                'delivery_target_date' => $data['deliveryTargetDate'],
                'targetCustomer' => $data['targetCustomer'],
                'fitReference' => $data['fitReference'],
                'category' => $data['category'],
                'styles_count' => intval($data['stylesCount']),
                'vlp_attachments' => isset($data['vlpAttachments']) ? $data['vlpAttachments'] : null,
            ];
            $updated = $this->update($data, $lineId);

            if ($updated) {
                return $this->find($lineId);
            }

            return false;
	}

	/**
	 * @param  array $request
	 * @return mixed
	 */
	public function filterLine($data)
	{
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
	}

    public function getAllLine($command)
    {
        return $this->model->all();
    }

    public function deleteLine($id)
    {
        \App\Style::where('line_id', $id)->delete();
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Archive Line
     * @param  string $id
     * @return boolean
     */
    public function archiveLine($id)
    {
        $line = $this->model->where('id', $id)->first();
        $archived = $line->update(['archived_at' => Carbon::now()]);
        if ($archived && !empty($line->styles)) {
            foreach ($line->styles as $style) {
                \DB::table('styles')->where('id', $style->id)
                    ->update(['archived_at' => Carbon::now()]);
            
                if (!empty($style->sampleContainer)) {
                    \DB::table('sample_containers')
                        ->where('id', $style->sampleContainer->id)
                        ->update(['archived_at' => Carbon::now()]);
                }
                if (!empty($style->tna)) {
                    \DB::table('tna')
                        ->where('id', $style->tna->id)
                        ->update(['archived_at' => Carbon::now()]);
                }
                if (!empty($style->techpack)) {
                    \DB::table('techpacks')
                        ->where('id', $style->techpack->id)
                        ->update(['archived_at' => Carbon::now()]);
                }
            }
        }
        return $archived;
    }

    /**
     * complete the line
     * @param  string $id 
     * @return boolean
     */
    public function completeLine($id)
    {
        $line = $this->model->where('id', $id)->first();
        $completed = $line->update(['completed_at' => Carbon::now()]);
        if ($completed && !empty($line->styles)) {
            foreach ($line->styles as $style) {
                \DB::table('styles')->where('id', $style->id)
                    ->update(['completed_at' => Carbon::now()]);
            
                if (!empty($style->sampleContainer)) {
                    \DB::table('sample_containers')
                        ->where('id', $style->sampleContainer->id)
                        ->update(['completed_at' => Carbon::now()]);
                }
                if (!empty($style->tna)) {
                    \DB::table('tna')
                        ->where('id', $style->tna->id)
                        ->update(['completed_date' => Carbon::now()]);
                }
                if (!empty($style->techpack)) {
                    \DB::table('techpacks')
                        ->where('id', $style->techpack->id)
                        ->update(['completed_at' => Carbon::now()]);
                }
            }
        }   
        return $completed;
    }

    /**
     * undo the line
     * @param  string $id 
     * @return boolean     
     */
    public function undoLine($id)
    {
        $line = $this->model->where('id', $id)->first();
        $undo = $line->update(['completed_at' => NULL]);
        if ($undo && !empty($line->styles)) {
            foreach ($line->styles as $style) {
                \DB::table('styles')->where('id', $style->id)
                    ->update(['completed_at' => NULL]);
            
                if (!empty($style->sampleContainer)) {
                    \DB::table('sample_containers')
                        ->where('id', $style->sampleContainer->id)
                        ->update(['completed_at' => NULL]);
                }
                if (!empty($style->tna)) {
                    \DB::table('tna')
                        ->where('id', $style->tna->id)
                        ->update(['completed_date' => NULL]);
                }
                if (!empty($style->techpack)) {
                    \DB::table('techpacks')
                        ->where('id', $style->techpack->id)
                        ->update(['completed_at' => NULL]);
                }
            }
        }   
        return $undo;
    }

    /**
     * Rollback Line
     * @param  string $id
     * @return boolean
     */
    public function rollbackLine($id)
    {
        $line = $this->model->where('id', $id)->first();
        $rollback = $line->update(['archived_at' => NULL]);
        if ($rollback && !empty($line->styles)) {
            foreach ($line->styles as $style) {
                \DB::table('styles')->where('id', $style->id)
                    ->update(['archived_at' => NULL]);
            
                if (!empty($style->sampleContainer)) {
                    \DB::table('sample_containers')
                        ->where('id', $style->sampleContainer->id)
                        ->update(['archived_at' => NULL]);
                }
                if (!empty($style->tna)) {
                    \DB::table('tna')
                        ->where('id', $style->tna->id)
                        ->update(['archived_at' => NULL]);
                }
                if (!empty($style->techpack)) {
                    \DB::table('techpacks')
                        ->where('id', $style->techpack->id)
                        ->update(['archived_at' => NULL]);
                }
            }
        }
        return $rollback;
    }

    /**
     * Get All Lines created today
     * This method is getting called for sending digest notification
     * @param  string $id
     * @return mixed
     */
    public function getTodayCreatedLineList($id)
    {
        return $this->model->where('created_at','>', Carbon::today())
                            ->whereNULL('archived_at')
                            ->whereNULL('deleted_at')
                            ->where(function($query) use ($id){
                                $query->orWhere('sales_representative_id', $id);
                                $query->orWhere('production_lead_id', $id);
                                $query->orWhere('product_development_lead_id', $id);
                                $query->orWhere('merchandiser_id', $id);
                            })
                            ->get()
                            ->toArray();
    }

    /**
     * Get All Lines archived today
     * This method is getting called for sending digest notification
     * @param  string $id
     * @return mixed
     */
    public function getTodayArchivedLineList($id)
    {
        return $this->model->where('created_at','>', Carbon::today())
                            ->whereNULL('deleted_at')
                            ->where(function($query) use ($id){
                                $query->orWhere('sales_representative_id', $id);
                                $query->orWhere('production_lead_id', $id);
                                $query->orWhere('product_development_lead_id', $id);
                                $query->orWhere('merchandiser_id', $id);
                            })
                            ->get()
                            ->toArray();
    }

    /**
     * Check if a user is related to a line or not
     * This method is getting called for sending digest notification
     * @param  string $lineId, $userId
     * @return mixed
     */
    public function isUserFoundInLine($lineId, $userId)
    {
        return $this->model->where('id', $lineId)
                            ->where(function($query) use ($userId){
                                $query->orWhere('sales_representative_id', $userId);
                                $query->orWhere('production_lead_id', $userId);
                                $query->orWhere('product_development_lead_id', $userId);
                                $query->orWhere('merchandiser_id', $userId);
                            })
                            ->first();
    }
}
