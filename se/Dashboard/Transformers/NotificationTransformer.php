<?php

namespace Platform\Dashboard\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use app\ImmediateActionRule;

class NotificationTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(ImmediateActionRule $notification)
    {
        dd($notification->toArray());
        return [
            'isRead' => ''
        ];
    }
}

