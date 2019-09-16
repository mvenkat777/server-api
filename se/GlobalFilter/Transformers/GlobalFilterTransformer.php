<?php

namespace Platform\GlobalFilter\Transformers;

use App\Techpack;
use App\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class GlobalFilterTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform($data)
    {
    	$response = [
    		'id' => $data->id,
    		'name' => isset($data->name) ? $data->name : (isset($data->title)? 
                $data->title : (isset($data->display_name)? $data->display_name : NULL)),
            'isEditable' => isset($data->isEditable)? $data->isEditable : NULL,
            'email' => isset($data->email) ? $data->email : NULL
    	];
        if ($response['email'] == NULL || !preg_match('/^@/', $response['name'])) {
            unset($response['email']);
        }
        if ($response['isEditable'] === NULL) {
            unset($response['isEditable']);
        }
        return $response;
    }
}
