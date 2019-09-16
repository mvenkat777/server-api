<?php

namespace Platform\Line\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class VLPApprovalTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($vlpApproval)
	{
		return [
            'id' => $vlpApproval->id,
            'approver' => $vlpApproval->approver()->select('id', 'display_name as displayName', 'email')
                              ->first(),
            'approval' => $vlpApproval->approval,
            'approvedAt' => $vlpApproval->created_at->toDateTimeString(),
        ];
	}

}