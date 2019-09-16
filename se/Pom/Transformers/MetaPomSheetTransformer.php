<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class MetaPomSheetTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pomSheet)
	{
		$isDeletable = $this->isDeletable();

		$data = [
			'KEY' => [$pomSheet->key? "*":"", ""],
			'QC' => [$pomSheet->qc? "*" : "", ""],
			'POM_CODE' => [$pomSheet->code, ""],
			'POM_DESCRIPTION' => [$pomSheet->description, ""],
			'TOL' => [$pomSheet->tol, ""],
			'isDeletable' => $isDeletable,
			'archivedAt' => !is_null($pomSheet->archived_at)? $pomSheet->archived_at->toDateTimeString() : NULL
		];
		foreach (json_decode($pomSheet->data) as $key => $value) {
		 	$data[$key] = [$value, ""];
		} 
		return $data;
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