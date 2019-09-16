<?php

namespace Platform\Line\Transformers;

use App\StyleDevelopment;
use League\Fractal\TransformerAbstract;

class StyleDevelopmentTransformer extends TransformerAbstract
{
    public function transform(StyleDevelopment $development)
    {
    	if($development->pivot){
	        $devlpmnt = [
	        	'id' => $development->id,
	        	'name' => (string)$development->name,
	        	'isParallel' => $development->is_parallel,
	            'owner' => json_decode($development->pivot['owner']),
	            'isApproved' => $development->pivot['is_approved'],
	            'approvedAt' => $development->pivot['approved_at'],
	            'approvedBy' => json_decode($development->pivot['approved_by']),
	            'unapprovedAt' => $development->pivot['unapproved_at'],
	            'unapprovedBy' => json_decode($development->pivot['unapproved_by']),
	            'isEnabled' => true, //$development->pivot['is_enabled'],
	            // 'isEditable' => \Auth::user()->email == json_decode($development->pivot['owner'])->email
	        ];
	    }
	    else{
	    	$devlpmnt = [
	           'id' => $development->id,
	           'name' => (string)$development->name
	        ];
	    }
	    return $devlpmnt;
    }
}
