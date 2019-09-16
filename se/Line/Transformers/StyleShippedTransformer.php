<?php

namespace Platform\Line\Transformers;

use App\StyleShipped;
use League\Fractal\TransformerAbstract;

class StyleShippedTransformer extends TransformerAbstract
{
    public function transform(StyleShipped $shipped)
    {
    	if($shipped->pivot){
	        $ship = [
	        	'id' => $shipped->id,
	        	'name' => (string)$shipped->name,
	        	'isParallel' => $shipped->is_parallel,
	            'owner' => json_decode($shipped->pivot['owner']),
	            'isApproved' => $shipped->pivot['is_approved'],
	            'approvedAt' => $shipped->pivot['approved_at'],
	            'approvedBy' => json_decode($shipped->pivot['approved_by']),
	            'unapprovedAt' => $shipped->pivot['unapproved_at'],
	            'unapprovedBy' => json_decode($shipped->pivot['unapproved_by']),
	            'isEnabled' => true, //$shipped->pivot['is_enabled'],
	            // 'isEditable' => \Auth::user()->email == json_decode($shipped->pivot['owner'])->email
	        ];
	    }
	    else{
	    	$ship = [
	           'id' => $shipped->id,
	           'name' => (string)$shipped->name
	        ];
	    }
	    return $ship;
    }
}
