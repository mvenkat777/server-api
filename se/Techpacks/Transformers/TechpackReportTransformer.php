<?php

namespace Platform\Techpacks\Transformers;

use App\Techpack;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class TechpackReportTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(Techpack $techpack)
    {
        return [
            'id' => (string) $techpack->id,
            'name' => (string) $techpack->meta->name,
            'styleCode' => (string) $techpack->meta->styleCode,
            'category' => $techpack->meta->category,
            'product' => $techpack->meta->product,
            'visibility' => $techpack->visibility,
            'productType' => $techpack->meta->productType,
            'collection' => $techpack->meta->collection,
            'sizeType' => $techpack->meta->sizeType,
            'season' => $techpack->meta->season,
            'stage' => $techpack->meta->stage,
            'revision' => $techpack->meta->revision,
            'state' => $techpack->meta->state,
            'createdAt' => $techpack->created_at->toDateTimeString(),
            'updatedAt' => $techpack->updated_at->toDateTimeString(),
        ];
    }
}
