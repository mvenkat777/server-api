<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Pom\Transformers\SizeRangeTransformer;

class MetaPomTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pom)
	{
		$fractal = new Manager();

		// $sizeRange = new Item($pom->sizeRange, new SizeRangeTransformer());
  //       $sizeRange = $fractal->createData($sizeRange)->toArray()['data'];
     	   
        $isDeletable = $this->isDeletable();

		return [
			'id' => $pom->id,
			'name' => strtoupper($pom->name),
			'category' => $pom->category['category'],
			'productType' => $pom->productType['product_type'],
			'sizeType' => $pom->sizeType['size_type'],
			'baseSize' => strtoupper($pom->base_size),
			'isDeletable' => $isDeletable,
			'sizeRangeName' => strtoupper($pom->size_range_name),
			'sizeRangeValue' => explode(',', strtoupper(implode(',', json_decode($pom->size_range_value)))),
			'archivedAt' => !is_null($pom->archived_at)? $pom->archived_at->toDateTimeString() : NULL
		];
	}

	/**
     * Add POM Delete permission as per user
     * @param array $POM 
     */
    public function isDeletable()
    {
        $role = \App\Role::where('name', 'Delete Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();

        if (empty($userIds)) {
            return (\Auth::user()->is_god === true); 
        }
        return (
            in_array(\Auth::user()->id, $userIds)  || 
            \Auth::user()->is_god === true
        ); 
    }
}