<?php

namespace Platform\App\Activity\Transformers;

use League\Fractal\TransformerAbstract;
use App\ActivityModel\NotificationModel\NotifyTarget;

class ActivityTransformer extends TransformerAbstract
{
    public function transform (NotifyTarget $activity)
    {
        return [
            'message' => $activity->target['message']
        ];
    }
}