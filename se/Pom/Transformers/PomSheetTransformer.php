<?php

namespace Platform\Pom\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class PomSheetTransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($pomSheet)
	{
		$isDeletable = $this->isDeletable();
		return [
			'id' => $pomSheet->id,
			'qc' => $pomSheet->qc,
			'key' => $pomSheet->key,
			'code' => $pomSheet->code,
			'isDeletable' => $isDeletable,
			'description' => $pomSheet->description,
			'tol' => $pomSheet->tol,
			'data' => json_decode($pomSheet->data),
			'archivedAt' => !is_null($pomSheet->archived_at)? $pomSheet->archived_at->toDateTimeString() : NULL
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