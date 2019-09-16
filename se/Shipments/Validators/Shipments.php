<?php 

namespace Platform\Shipments\Validators;

use Platform\App\Validation\DataValidator;

class Shipments extends DataValidator
{
	protected $rules = [
		'shipmentType'=>'required',
	    'shippedDate'=>'required',
	    'shippedFrom'=>'required',
	    'shippedDestination'=>'required',
	    'itemDetails'=>'',
	    'trackingId'=>'',
	    'trackingProvide'=>'',
	    'techpackID'=>'',
	    'productId'=>''
	];
}
