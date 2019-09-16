<?php

namespace Platform\Line\Transformers;

use App\StyleProduction;
use League\Fractal\TransformerAbstract;

class StyleProductionTransformer extends TransformerAbstract
{
    public function transform(StyleProduction $production)
    {
    	if($production->pivot){
	        $produc = [
	        	'id' => $production->id,
	        	'name' => (string)$production->name,
	        	'isParallel' => $production->is_parallel,
	            'owner' => json_decode($production->pivot['owner']),
	            'isApproved' => $production->pivot['is_approved'],
	            'approvedAt' => $production->pivot['approved_at'],
	            'approvedBy' => json_decode($production->pivot['approved_by']),
	            'unapprovedAt' => $production->pivot['unapproved_at'],
	            'unapprovedBy' => json_decode($production->pivot['unapproved_by']),
	            'isEnabled' => true, //$production->pivot['is_enabled'],
	            // 'isEditable' => \Auth::user()->email == json_decode($production->pivot['owner'])->email
	        ];
	    }
	    else{
	    	$produc = [
	           'id' => $production->id,
	           'name' => (string)$production->name
	        ];
	    }
	    return $produc;
    }
}
