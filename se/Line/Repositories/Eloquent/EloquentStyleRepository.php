<?php

namespace Platform\Line\Repositories\Eloquent;

use App\Style;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Line\Repositories\Contracts\StyleRepository;

class EloquentStyleRepository extends Repository implements StyleRepository 
{

	public function model() {
		return 'App\Style';
	}

    public function styleLists($data, $lineId)
    {
        if (isset($data['type']) && $data['type'] == 'archived') {
            return $this->model->where('line_id', $lineId)
                ->whereNotNull('archived_at')->get();
        }
        return $this->model->where('line_id', $lineId)
                ->whereNull('archived_at')->get();
    }

	/**
	 * Creates a new style in the line with line id $lineId
	 *
	 * @param string $lineId
	 * @return mixed
	 */
	public function createNewStyle($data) {

            $data = [
                'id' => $this->generateUUID(),
                'name' => $data['name'],
                'line_id' => $data['lineId'],
                'product_brief' => isset($data['productBrief']) ? json_encode($data['productBrief']) : null,
                'customer_style_code' => isset($data['customerStyleCode']) ? $data['customerStyleCode'] : null,
            ];

            return $this->create($data);
	}

	/**
	 * Update a style
	 *
	 * @param string $lineId
	 * @param string $styleId
	 * @param array $data
	 * @return mixed
	 */
	public function updateStyle($lineId, $styleId, $data) {
		$style = [];

		if (isset($data['name'])) {
			$style['name'] = $data['name'];
		}

		if (isset($data['styleCode'])) {
			$style['code'] = $data['styleCode'];
		}

		if (isset($data['techpackId']) && !empty($data['techpackId'])) {
			$style['techpack_id'] = $data['techpackId'];
		}

		if (isset($data['flat'])) {
			$style['flat'] = $data['flat'];
		}

		if (isset($data['tnaId'])) {
			$style['tna_id'] = $data['tnaId'];
		}

		if (isset($data['orderId'])) {
			$style['order_id'] = $data['orderId'];
                }

		if (isset($data['productBrief'])) {
			$style['product_brief'] = json_encode($data['productBrief']);
                }

		if (isset($data['customerStyleCode'])) {
			$style['customer_style_code'] = $data['customerStyleCode'];
                }

		$this->update($style, $styleId);
		return $this->model->with([
			'techpack', 'tna', 'sampleSubmissions', 'order'
		])->find($styleId);
	}	

    /**
     * get style by id
     * @param  string $styleId 
     * @return style          
     */
    public function getStyleById($styleId)
    {
        return $this->model->find($styleId);
    }

	/**
	 * Get a style based on lineId and styleId
	 *
	 * @param string $lineId
	 * @param string $styleId
	 * @return mixed
	 */
	public function getByLineIdAndStyleId($lineId, $styleId) {
            return $this->model->where('line_id', $lineId)
                                ->where('id', $styleId)
                                ->with([
                                    'techpack', 'tna', 'sampleSubmissions', 'order'
                                ])->first();
        }	

	/**
	 * Get a style based on techpackId
	 *
	 * @param string $techpackId
	 * @return mixed
	 */
	public function getByTechpackId($techpackId) {
            return $this->model->where('techpack_id', $techpackId)
                                ->with([
                                    'techpack', 'tna', 'sampleSubmissions', 'order'
                                ])->first();
    }	

    /**
     * @param string $styleId     
     * @param array $devlopement 
     */
    public function addDevelopmentApprovelChecklist($styleId, $development)
    {
        $this->model->find($styleId)->development()->sync($development);
    }

    /**
     * @param string $styleId     
     * @param array $production 
     */
    public function addProductionApprovelChecklist($styleId, $production)
    {
        $this->model->find($styleId)->production()->sync($production);
    }

    /**
     * @param string $styleId     
     * @param array $shipped 
     */
    public function addShippedApprovelChecklist($styleId, $shipped)
    {
        $this->model->find($styleId)->shipped()->sync($shipped);
    }

    /**
     * @param string $styleId     
     * @param array $reviewd 
     */
    public function addReviewApprovelChecklist($styleId, $review)
    {
        $this->model->find($styleId)->review()->sync($review);
    }

    /**
     * @param  string $table          
     * @param  string $field          
     * @param  string $styleId        
     * @param  integer $approvalNameId 
     */
    public function approvedChecklist($table, $field, $styleId, $approvalNameId)
    {
        $data = \DB::table($table)->where('style_id',$styleId)
            ->where($field, $approvalNameId)->first();
        //if (!$data->is_enabled) {
        //   throw new SeException("This Checklist is not enabled yet.", 400, 4000804);
        //}
    	\DB::table($table)->where('style_id',$styleId)
            ->where($field, $approvalNameId)
            ->update(['is_approved' => true, 
                    'approved_at' => Carbon::now(), 
                    'approved_by' => \Auth::user(),
                    'is_enabled' => true]);
        $style = $this->model->find($styleId);

    }

    /**
     * @param  string $table          
     * @param  string $field          
     * @param  string $styleId        
     * @param  integer $approvalNameId 
     */
    public function unapprovedChecklist($table, $field, $styleId, $approvalNameId)
    {
    	\DB::table($table)->where('style_id',$styleId)
            ->where($field, $approvalNameId)
            ->update(['is_approved'=>false, 
                    'approved_at'=>null,
                    'approved_by'=>null, 
                    'is_enabled'=>true,
                    'unapproved_by' => \Auth::user(),
                    'unapproved_at' => Carbon::now()
                ]);
        $style = $this->model->find($styleId);
    }

    /**
     * @param  string $table       
     * @param  string $field   
     * @param  string $styleId     
     * @param  integer $checklistId 
     * @return  boolean            
     */
    public function enableApprovalChecklist($table, $field, $styleId, $checklistId)
    {
        return \DB::table($table)->where('style_id',$styleId)
            ->where($field, $checklistId)
            ->update(['is_enabled' => true]);
    }

    /**
     * get All style
     * @param  array $command 
     * @return list
     */
    public function getAllStyle($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);

        // return $this->model
        //     ->join('lines', 'styles.line_id', '=', 'lines.id')
        //     ->select('styles.*')
        //     ->orderBy('lines.delivery_target_date', 'ASC')
        //     ->with(['line', 'development', 'production'])
        //     ->paginate($command->item);
    }

    /**
     * Archive style 
     * @param  string $id 
     * @return boolean
     */
    public function archiveStyle($lineId, $styleId)
    {
        $style = $this->model->where('id', $styleId)->first();
        $archived = $style->update(['archived_at' => Carbon::now()]);
        if ($archived) {
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
        return $archived;   
    }

    /**
     * Complete the style
     * @param  string $lineId  
     * @param  string $styleId 
     * @return boolean          
     */
    public function completeStyle($lineId, $styleId)
    {
        $style = $this->model->where('id', $styleId)->first();
        $completed = $style->update(['completed_at' => Carbon::now()]);
        if ($completed) {
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
        return $completed;
    }

    /**
     * Undo the style
     * @param  string $lineId  
     * @param  string $styleId 
     * @return boolean          
     */
    public function undoStyle($lineId, $styleId)
    {
        $style = $this->model->where('id', $styleId)->first();
        $completed = $style->update(['completed_at' => NULL]);
        if ($completed) {
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
        return $completed;
    }

    /**
     * Rollback Style
     * @param  string $id 
     * @return boolean     
     */
    public function rollbackStyle($lineId, $styleId)
    {
        $style = $this->model->where('id', $styleId)->first();
        $rollback = $style->update(['archived_at' => NULL]);
        if ($rollback) {
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
        return $rollback;
    }

    /**
     * Get All Styles created today 
     * This method is getting called for sending digest notification
     * @param  string $id 
     * @return mixed   
     */
    public function getTodayCreatedStyleList()
    {
        return $this->model->where('created_at','>', Carbon::today())
                            ->whereNULL('archived_at')
                            ->whereNULL('deleted_at')
                            ->get()
                            ->toArray();
    }

    /**
     * Get All Styles archived today 
     * This method is getting called for sending digest notification
     * @param  string $id 
     * @return mixed   
     */
    public function getTodayArchivedStyleList()
    {
        return $this->model->where('archived_at','>', Carbon::today())
                            ->whereNULL('deleted_at')
                            ->get()
                            ->toArray();
    }
}
