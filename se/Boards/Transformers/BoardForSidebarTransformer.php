<?php

namespace Platform\Boards\Transformers;

use App\Board;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Picks\Transformers\PickTransformer;
use Platform\Techpacks\Transformers\TechpackUserTransformer;

class BoardForSidebarTransformer extends TransformerAbstract
{
    public function transform(Board $board)
    {
        $fractal = new Manager();
        $collaborators = $board->collaborators()->having('permission', '=', 'collaborator')->get();
        $collaborators = new Collection($collaborators, new TechpackUserTransformer);
        $collaborators = $fractal->createData($collaborators)->toArray();

        $picks = $board->picks()->limit(6)->get();
        $transformedPicks = [];

        foreach ($picks as $pick) {
            array_push($transformedPicks, [
                'id' => $pick->id,
                'image' => $pick->image,
                'title' => $pick->title,
            ]);
        }

        $owner = $board->owner()->having('permission', '=', 'owner')->first();
        $owner = (new TechpackUserTransformer)->transform($owner);

        $meta['owner'] = $owner;
        $meta['collaborators'] = $collaborators['data'];

        return [
            'id'          => $board->id,
            'name'        => $board->name,
            'description' => $board->description,
            'category'    => $board->category,
            'createdOn'   => $board->created_at->toDateTimeString(),
            'meta'        => $meta,
            'picks'       => $transformedPicks,
            'updatedAt'   => $board->updated_at->toDateTimeString(),
        ];
    }
}
