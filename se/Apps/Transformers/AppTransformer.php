<?php

namespace Platform\Apps\Transformers;

use League\Fractal\TransformerAbstract;
use App\Apps;

class AppTransformer extends TransformerAbstract
{
    public function transform(Apps $app)
    {
        $data = [
            'appId' => (int)$app->id,
            'appName' => (string)$app->app_name
        ];
        if(isset($app->status)){
            $data['status'] = (string)$app->status;
        }        
        // if(isset($app->pivot->userId)){
        // 	$data['userId'] = (string)$app->pivot->userId;
        // }
        // if(isset($app->pivot->permission)){
        // 	$data['permission'] = (string)$app->pivot->permission;
        // }

        return $data;
    }

}
