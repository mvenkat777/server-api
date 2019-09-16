<?php

namespace Platform\Techpacks\Transformers;

use App\Colorway;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class ColorwaysTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(Colorway $colorway)
    {
        return [
            'id' => (string) $colorway->id,
            'bomLineItemId' => (string) $colorway->bom_line_item_id,
            'colorway' => $colorway->colorway,
            'approval' => $colorway->approval,
            'createdAt' => date(DATE_ISO8601, strtotime($colorway->created_at)),
        ];
    }
}
