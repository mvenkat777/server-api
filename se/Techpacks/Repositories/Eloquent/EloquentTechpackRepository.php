<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Criteria\OrderBy;
use Platform\App\Repositories\Criteria\OrderByCreatedAt;
use Platform\App\Repositories\Criteria\Where;
use Platform\App\Repositories\Criteria\WhereIn;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Commands\AssociateTechpackCommand;
use Platform\Techpacks\Commands\DeleteTechpackCommand;
use Platform\Techpacks\Commands\ForceDeleteTechpackCommand;
use Platform\Techpacks\Commands\GenerateTechpackSchemaCommand;
use Platform\Techpacks\Commands\GetTechpackByIdCommand;
use Platform\Techpacks\Commands\GetTechpackSchemaCommand;
use Platform\Techpacks\Commands\ListTechpacksCommand;
use Platform\Techpacks\Commands\RegisterNewTechpackCommand;
use Platform\Techpacks\Commands\RestoreTechpackCommand;
use Platform\Techpacks\Commands\UpdateTechpackCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Illuminate\Support\Facades\Auth;

class EloquentTechpackRepository extends Repository implements TechpackRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'App\Techpack';
    }

    /**
     * Get meta of all techpacks
     * @return mixed
     */
    public function getMeta()
    {
        return $this->model->select(['id', 'meta'])
                           ->get();
    }

	/**
	 * Get all accessible techpacks of the logged in user
	 *
	 * @return mixed
	 */
	public function getAllAccessibleTechpacks() {
		return $this->model->where('user_id', Auth::user()->id)
							 ->orWhere('visibility', true)
							 ->get();
	}



    /**
     * @param ListTechpacksCommand $command
     *
     * @return mixed
     */
    public function listTechpacks(ListTechpacksCommand $command, $user)
    {
        $this->pushCriteria(new OrderBy('updated_at', 'desc'));
        $techpack_list = [];
        $techpack_list_owned = [];
        if ($command->withScope == 'se') {
            $techpack_list_se = DB::table('techpacks')->where('is_se_owned', 1)
                                                      ->where('is_se_approved', 1)
                                                      ->lists('id');
            $techpack_list = $techpack_list_se;
        } else {
            $techpack_list_owned = DB::table('techpack_user')->where('user_id', '=', $user->id)
                                                             ->where('permission', 'owner')
                                                             ->lists('techpack_id');
            $techpack_list = $techpack_list_owned;
            if ($command->withScope == 'others') {
                if ($user->is_admin || $user->is_delegated_admin) {
                    $techpack_list_others = DB::table('techpacks')->lists('id');
                } else {
                    $techpack_list_others = DB::table('techpacks')->where('visibility', '=', 1)
                                                                  ->where('is_se_owned', '<>', 1)
                                                                  ->where('is_se_approved', '1')
                                                                  ->lists('id');
                }
                $techpack_list = array_diff($techpack_list_others, $techpack_list_owned);
            }
        }
        $this->pushCriteria(new WhereIn('id', $techpack_list));
        if ($command->withCollection != 'all') {
            $this->pushCriteria(new Where('collection', $command->withCollection, '='));
        }
        // try {
        if ($command->withTrashed) {
            if ($command->app == 'builder') {
                $techpacks = $this->paginateAll($command->item);
            } else {
                $techpacks = $this->paginateAll(
                    $command->item,
                    ['id', 'version', 'meta', 'user_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at']
                );
            }
        } elseif ($command->onlyTrashed) {
            if ($command->app == 'builder') {
                $techpacks = $this->paginateTrashed($command->item);
            } else {
                $techpacks = $this->paginateTrashed(
                    $command->item,
                    ['id', 'version', 'meta', 'user_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at']
                );
            }
        } else {
            if ($command->app == 'builder') {
                $techpacks = $this->paginate($command->item);
            } else {
                $techpacks = $this->paginate(
                    $command->item,
                    ['id', 'version', 'meta', 'user_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at']
                );
            }
        }
        // } catch (\Exception $e) {
        //     throw new \Exception("We messed up!", "500123456");
        // }
        if (!$techpacks) {
            throw new \Exception(
                'We are unable to find techpacks.',
                '200123456'
            );
        }

        return $techpacks;
    }

    /**
     * @param RegisterNewTechpackCommand $command
     * @param $isAdmin
     *
     * @return mixed
     */
    public function registerNewTechpack(RegisterNewTechpackCommand $command, $user_id)
    {
        $techpack = [
            'id' => $this->generateUUID(),
            'version' => $command->version,
            'name' => ucwords($command->name),
	        'customer_id' => $command->customer_id,
            'style_code' => $command->style_code,
            'category' => strtolower($command->category),
            'product' => strtolower($command->product),
            'product_type' => $command->product_type,
            'collection' => $command->collection,
            'size_type' => strtolower($command->size_type),
            'season' => $command->season,
            'stage' => strtolower($command->stage),
            'visibility' => (boolean) $command->visibility,
            'image' => $command->image,
            'revision' => $command->revision,
            'is_builder_techpack' => (boolean) $command->is_builder_techpack,
            'is_published' => (boolean) $command->is_published,
            'state' => $command->state,
            'meta' => $command->meta,
            'bill_of_materials' => $command->bill_of_materials,
            'poms' => $command->poms,
            'spec_sheets' => $command->spec_sheets,
            'color_sets' => $command->color_sets,
            'sketches' => $command->sketches,
            'user_id' => $user_id,
            'parent_id' => '',
            'data' => $command->data,
        ];
        try {
            DB::beginTransaction();
            $techpack = $this->create($techpack);
            $this->attach($techpack->id, 'users', $user_id, ['permission' => 'owner']);
            DB::commit();
        } catch (\Exception $e) {
            throw new \Exception('We are unable to save the techpack.', '500123456');
        }
        return $this->getTechpackById(new GetTechpackByIdCommand($techpack->id));
    }

    /**
     * @param GetTechpackByIdCommand $command
     *
     * @return mixed
     */
    public function getTechpackById(GetTechpackByIdCommand $command)
    {
        try {
            if ($command->withTrashed) {
                if ($command->getFields && $command->getFields != []) {
                    $command->getFields = array_merge($command->getFields, ['id', 'user_id']);
                    $techpack = $this->model->where('id', $command->id)
                                            ->where('deleted_at', '!=', null)
                                            ->first($command->getFields);
                } else {
                    $techpack = $this->findTrashed($command->id);
                }
            } else {
                if ($command->getFields && $command->getFields != []) {
                    $command->getFields = array_merge($command->getFields, ['id', 'user_id']);
                    $techpack = $this->model->where('id', $command->id)
                                            ->first($command->getFields);
                } else {
                    $techpack = $this->find($command->id);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('We messed up!', '500123456');
        }
        if (!$techpack) {
            throw new \Exception("We couldn't find techpack with that identity.", '404123456');
        }
        return $techpack;
    }

    /**
     * @param UpdateTechpackCommand $command
     *
     * @return mixed
     */
    public function updateTechpack(UpdateTechpackCommand $command)
    {
        $techpack = $this->model->where('id', $command->id)->first();

        if (!is_null($techpack->locked_at)) {
            throw new SeException("Techpack is Locked", 500, 5000123);
            
        }
        $techpackUpdated = [
            'name' => ucwords($command->name),
	        'customer_id' => $command->customer_id,
            'style_code' => $command->style_code,
            'category' => strtolower($command->category),
            'product' => strtolower($command->product),
            'product_type' => $command->product_type,
            'collection' => $command->collection,
            'size_type' => strtolower($command->size_type),
            'season' => $command->season,
            'stage' => strtolower($command->stage),
            'visibility' => (boolean) $command->visibility,
            'image' => $command->image,
            'revision' => $command->revision,
            'is_builder_techpack' => $command->is_builder_techpack,
            'is_published' => $command->is_published,
            'state' => $command->state,
            'meta' => $command->meta,
            'bill_of_materials' => $command->bill_of_materials,
            'poms' => $command->poms,
            'spec_sheets' => $command->spec_sheets,
            'color_sets' => $command->color_sets,
            'sketches' => $command->sketches,
            'data' => $command->data,
        ];
        try {
            \DB::beginTransaction();
            $this->update($techpackUpdated, $command->id);
            \DB::commit();
        } catch (\Exception $e) {
            throw new \Exception('We messed up!', '500123456');
        }

        return $this->getTechpackById(new GetTechpackByIdCommand($command->id));
    }

    /**
     * @param DeleteTechpackCommand $command
     *
     * @return bool
     */
    public function deleteTechpack(DeleteTechpackCommand $command)
    {
        $techpack = $this->getTechpackById(new GetTechpackByIdCommand($command->id, true));
        if ($techpack) {
            if($techpack->sampleContainer) {
                $techpack->sampleContainer->delete();
            }
            $techpack->delete();
        }

        return true;
    }

    /**
     * @param RestoreTechpackCommand $command
     *
     * @return bool
     */
    public function restoreTechpack(RestoreTechpackCommand $command)
    {
        $this->getTechpackById(new GetTechpackByIdCommand($command->id, true));
        try {
            $this->restore($command->id);
        } catch (\Exception $e) {
            throw new \Exception('We messed up!', '500123456');
        }

        return true;
    }

    /**
     * @param ForceDeleteTechpackCommand $command
     *
     * @return bool
     */
    public function forceDeleteTechpack(ForceDeleteTechpackCommand $command)
    {
        $this->getTechpackById(new GetTechpackByIdCommand($command->id, true));
        try {
            $this->forceDelete($command->id);
        } catch (\Exception $e) {
            throw new \Exception('We messed up!', '500123456');
        }

        return true;
    }

    public function getTechpackSchema(GetTechpackSchemaCommand $command)
    {
        $last_version = $command->version;
        if ($command->version == 0) {
            $last_version = DB::table('techpack_schemas')->max('id');
        }
        try {
            $results = DB::table('techpack_schemas')->where('id', $last_version)->first();
            if ($results) {
                return Response::json(json_decode($results->schema));
            }
        } catch (\Exception $e) {
            throw new \Exception('We messed up!', '500123456');
        }
        throw new \Exception('We couldnot find schema with that version!', '404123456');
    }

    public function generateTechpackSchema(
        GenerateTechpackSchemaCommand $command,
        $libraryItem,
        $user_id
    ) {
        $meta_v1 = [
            'name' => '',
            'styleCode' => '',
            'category' => '',
            'product' => '',
            'productType' => '',
            'collection' => '',
            'sizeType' => '',
            'season' => '',
            'stage' => '',
            'revision' => 0,
            'state' => '',
            'isPublished' => 0,
            'isBuilderTechpack' => 0,
            'visibility' => 'public',
            'image' => '',
            'tags' => ['techpack'],
        ];
        $libraryItem = $libraryItem::with('libraryItemAttribute')->orderBy('created_at')->get();
        $libraryItems = \App\LibraryItem::with([
            'libraryItemAttribute' => function ($query) {
                $query->orderBy('created_at');
            }
        ])->orderBy('created_at')->get();
        $bom = [];
        $libraryItemCount = 0;
        $libraryItemAttributeCount = 0;
        foreach ($libraryItems as $libraryItem) {
            $library_item_attributes = $libraryItem->libraryItemAttribute;
            $lia = [];
            $lia['id'] = '';
            foreach ($library_item_attributes as $library_item_attribute) {
                $lia[ $library_item_attribute->name ] = '';
            }

            $bom [ $libraryItemCount ] = [
                'id' => $libraryItem->id,
                'name' => $libraryItem->slug,
                'label' => $libraryItem->description,
                'rows' => [$lia],
            ];
            ++$libraryItemCount;
        }
        $techpack = [
            'version' => 1,
            'meta' => $meta_v1,
            'billOfMaterials' => $bom,
            'colorSets' => [],
            'poms' => [],
            'specSheets' => [],
            'sketches' => [
                ['label' => 'Photo', 'images' => []],
                ['label' => 'Construction', 'images' => []],
                ['label' => 'CAD', 'images' => []],
                ['label' => 'How to measure', 'images' => []],
                ['label' => 'Others', 'images' => []],

            ],
        ];

        try {
            DB::beginTransaction();
            $last_version = DB::table('techpack_schemas')->max('id');
            if (!$last_version) {
                $last_version = 1;
            } else {
                $techpack['version'] = ($last_version + 1);
            }
            $id = DB::table('techpack_schemas')->insertGetId(
                [
                    'schema' => json_encode($techpack),
                    'user_id' => $user_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('We messed up!', '500123456');
        }

        return $techpack;
    }

    public function associateTechpack(AssociateTechpackCommand $command)
    {
        $this->getTechpackById(new GetTechpackByIdCommand($command->id, true));
        if ($command->action == 'attach') {
            try {
                DB::beginTransaction();
                $this->detach($command->id, 'user', $command->user_id);
                $this->attach($command->id, 'user', $command->user_id, ['permission' => $command->permission]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception('We messed up!', '500123456');
            }
        } else {
            try {
                DB::beginTransaction();
                $this->detach($command->id, 'user', $command->user_id);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception('We messed up!', '500123456');
            }
        }
//        $associations = DB::table('techpack_user')->where('techpack_id',$command->id)->get();
//        return $associations;
//        return ($this->associationsRaw('techpack_user','techpack',$command->id));
//        dd(\App\Techpack::find('92BDE5D1-2A23-4761-9D2A-22D9DC02B48A')->user()->);
        return ($this->associations($command->id, 'user'));
    }

    public function getUserByTechpackId($techpackId)
    {
        return $this->model->find($techpackId)->user_id;
    }

    public function searchPublic($command)
    {
        return $this->model->where('visibility', '=', 1)
                           ->orderBy('updated_at', 'desc')
                           ->paginate($command->item);
    }

    public function cloneTechpack($command)
    {
        $clonedTechpack = $this->model->find($command->techpackId)->replicate();
        $clonedTechpack->id = $this->generateUUID();
        if (empty($clonedTechpack->parent_id)) {
            $clonedTechpack->parent_id = $command->techpackId;
        }
        $meta = (array)$clonedTechpack->meta;

        if ($command->name) {
            $clonedTechpack->name = $command->name;
            $meta['name'] = $command->name;
        }
        if ($command->customerId) {
            $clonedTechpack->customer_id = $command->customerId;
            $customer = \App\Customer::select(
                'id as customerId',
                'code',
                'name'
            )->where('id', $command->customerId)
            ->first();
            $meta['customer'] = $customer;
        }
        if ($command->category) {
            $clonedTechpack->category = $command->category;
            $meta['category'] = $command->category;
        }
        if ($command->product) {
            $clonedTechpack->product_type = $command->product;
            $meta['productType'] = $command->product;
        }
        if ($command->sizeType) {
            $clonedTechpack->size_type = $command->sizeType;
            $meta['sizeType'] = $command->sizeType;
        }
        if ($command->season) {
            $clonedTechpack->season = $command->season;
            $meta['season'] = $command->season;
        }

        $clonedTechpack->meta = (object) $meta;
        $clonedTechpack->user_id = $command->userId;
        $clonedTechpack->save();

        $boms = (array) $clonedTechpack->bill_of_materials;
        foreach ($boms as $bom) {
            foreach ($bom->rows as $bomLine) {
                $bomLineId = $bomLine->id;
                $bomLine->id = $this->generateUUID();
                $colorway = \App\Colorway::where('bom_line_item_id', $bomLineId)->first();
                if ($colorway) {
                    $colorway = $colorway->replicate();
                    $colorway->id = $this->generateUUID();
                    $colorway->techpack_id = $clonedTechpack->id;
                    $colorway->bom_line_item_id = $bomLine->id;
                    $colorway->save();
                }
            }
        }
        $cutTickets = \App\CutPiece::where('techpack_id', $command->techpackId)->get();
        foreach ($cutTickets as $cutTicket) {
            $newCutTicket = $cutTicket->replicate();
            $newCutTicket->id = $this->generateUUID();
            $newCutTicket->techpack_id = $clonedTechpack->id;
            $newCutTicket->save();
        }

        $cutTicketNotes = \App\TechpackCutTicketNote::where('techpack_id', $command->techpackId)
                                                      ->get();
        foreach ($cutTicketNotes as $cutTicketNote) {
            $newCutTicketNote = $cutTicketNote->replicate();
            $newCutTicketNote->techpack_id = $clonedTechpack->id;
            $newCutTicketNote->save();
        }

        $clonedTechpack->bill_of_materials = (object) $boms;
        $clonedTechpack->update();

        return $clonedTechpack;
    }

    /**
     * Check if authenticated  user it the owner of techpack.
     *
     * @param string $techpackId
     * @param string $userId
     * @return bool
     */
    public function isOwner($techpackId, $userId)
    {
        $techpack = $this->find($techpackId);
        if ($techpack) {
            return $techpack->user_id === $userId;
        }

        return false;
    }

    /**
     * Comple techpack
     * @param  string $id 
     * @return boolean
     */
    public function completeTechpack($id)
    {
        return $this->model->where('id', $id)
            ->update(['completed_at' => Carbon::now()]);
    }

    /**
     * Undo Techpack
     * @param  string $id 
     * @return boolean     
     */
    public function undoTechpack($id)
    {
        return $this->model->where('id', $id)
            ->update(['completed_at' => NULL]);

    }
    
    /**
     * Check if techpack is public.
     *
     * @param  $techpackId
     * @return bool
     */
    public function isPublic($techpackId)
    {
        return $this->find($techpackId)->visibility == 1;
    }

    public function filterTechpack($request)
    {
        $item = isset($request['item'])? $request['item'] : config('constants.listItemLimit');
        return $this->filter($request)->select(['id', 'user_id', 'name', 'meta', 'updated_at', 
            'archived_at', 'locked_at', 'locked_by', 'unlocked_by', 'unlocked_at'])
            ->where(function ($query) use ($request) {
                if ($request['owner'] == 'me') {
                    $query->where('user_id', \Auth::user()->id);
                } else if($request['owner'] == 'public') {
                    $query->where('visibility', true);
                } else {
                    $query->where('user_id', \Auth::user()->id)
                        ->orWhere('visibility', true);
                }
                if(isset($request['archived'])){
                    $query->whereNotNull('archived_at');
                } else {
                    $query->whereNull('archived_at');
                }
        })->paginate($item);
    }

    /**
     * Lock techpack
     * @param  string $id 
     * @return boolean     
     */
    public function lockTechpack($id)
    {
        $allowed = $this->isAllowToLock($id);
        if ($allowed) {
            $data = [
                'locked_at' => Carbon::now(),
                'unlocked_at' => NULL,
                'locked_by' => \Auth::user()->id,
                'unlocked_by' => NULL
            ];
            return $this->update($data, $id);
        }   
        throw new SeException("not allowed to lock", 500, 5000900);
        
    }

    /**
     * Unlock techpack
     * @param  string $id 
     * @return boolean     
     */
    public function unlockTechpack($code, $id)
    {
        $techpack = $this->model->where('id', $id)->first();
        if ($techpack->style_code != $code) {
            throw new SeException('Wrong style code', 403, 5000901);
        }
        $allowed = $this->isAllowToLock($id);
        if ($allowed) {
            $data = [
                'locked_at' => NULL,
                'unlocked_at' => Carbon::now(),
                'locked_by' => NULL,
                'unlocked_by' => \Auth::user()->id
            ];
            return $this->update($data, $id);
        }   
        throw new SeException("not allowed to lock", 500, 5000900);
        
    }

    /**
     * Checking The Permission
     * @param  string  $techpackId 
     * @return boolean             
     */
    public function isAllowToLock($techpackId)
    {
        $role = \App\Role::where('name', 'Edit Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();
        $techpack = $this->model->where('id', $techpackId)->first();

        if (empty($userIds)) {
            return (
                $techpack->user_id === \Auth::user()->id || 
                \Auth::user()->is_god === true
            ); 
        }
        return (
            $techpack->user_id === \Auth::user()->id || 
            in_array(\Auth::user()->id, $userIds)  || 
            \Auth::user()->is_god === true
        ); 
    }
}
