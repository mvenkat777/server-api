<?php

namespace Platform\Picks\Transformers;

use App\Pick;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Boards\Transformers\BoardTransformer;
use Platform\Techpacks\Transformers\TechpackUserTransformer;

class PickForPageTransformer extends TransformerAbstract
{
    public function transform(Pick $pick)
    {
        $fractal = new Manager();
        $owner = $pick->owner()->first();
        $owner = (new TechpackUserTransformer)->transform($owner);

        return [
            'id' => $pick->id,
            'image' => $pick->image,
            'title' => $pick->title,
            'description' => $pick->description,
            'owner' => $owner,
            'createdOn' => $pick->created_at,
        ];
    }
}
