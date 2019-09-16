<?php
namespace Platform\TNA\Transformers;

use League\Fractal\TransformerAbstract;

class TNAMetaDataTransformer extends TransformerAbstract
{
    public function transform($meta)
    {
        return [
            'categories' => $meta,
        ];
    }
}
