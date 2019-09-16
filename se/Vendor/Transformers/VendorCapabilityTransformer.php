<?php

namespace Platform\Vendor\Transformers;

use App\VendorCapability;
use League\Fractal\TransformerAbstract;

class VendorCapabilityTransformer extends TransformerAbstract
{
    public function transform(VendorCapability $capability)
    {
    	if($capability->pivot){
	        $capbl = [
	        	'id' => $capability->id,
	        	'name' => (string)$capability->name,
	            'inhouse' => $capability->pivot['inhouse'],
	            'outsource' => $capability->pivot['outsource'],
	            'moq' => $capability->pivot['moq'],
	            'capacity' => $capability->pivot['capacity'] 
	        ];
	    }
	    else{
	    	$capbl = [
	           'id' => $capability->id,
	           'name' => (string)$capability->name
	        ];
	    }
	    return $capbl;
    }
}