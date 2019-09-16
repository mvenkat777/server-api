<?php

namespace Platform\Materials\Transformers;

use App\Material;
use League\Fractal\TransformerAbstract;

class MaterialTransformer extends TransformerAbstract
{
    public function transform(Material $material)
    {
        $data = [
            'materialId' => $material->id,
            'materialReferenceNo' => (string) $material->material_reference_no,
            'materialType' => (string) $material->material_type,
            'construction' => (string) ($material->construction),
            'constructionType' => (string) ($material->construction_type),
            'fabricType' => (string) ($material->fabric_type),
            'fiber1' => (string) ($material->fiber_1),
            'fiber1Percentage' => (int) ($material->fiber_1_percentage),
            'fiber2' => (string) ($material->fiber_2),
            'fiber2Percentage' => (int) ($material->fiber_2_percentage),
            'fiber3' => (string) ($material->fiber_3),
            'fiber3Percentage' => (int) ($material->fiber_3_percentage),
            'otherFibers' => json_decode($material->other_fibers),
            'weight' => (int) ($material->weight),
            'weightUOM' => (string) ($material->weight_uom),
            'cuttableWidth' => (int) ($material->cuttable_width),
            'widthUOM' => (string) ($material->width_uom),
            'isReferred' => (bool) ($material->library()->count() == 0)?false:true
        ];

        return $data;
    }
}
