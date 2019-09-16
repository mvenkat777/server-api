<?php

namespace Platform\Boards\Transformers;

use App\SampleRequest;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class SampleRequestTransformer extends TransformerAbstract
{
    public function transform(SampleRequest $sampleRequest)
    {
        return [
            'id' => $sampleRequest->id,
            'boardId' => $sampleRequest->boardId,
            'userId' => $sampleRequest->userId,
            'status' => $sampleRequest->status,
            'createdOn' => $sampleRequest->created_at->toDateTimeString(),
        ];
    }
}
