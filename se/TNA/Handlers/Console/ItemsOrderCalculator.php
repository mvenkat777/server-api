<?php

namespace Platform\TNA\Handlers\Console;

use Platform\TNA\Models\TNAItem;
use Platform\TNA\Models\TNA;
use App\Http\Controllers\ApiController;
use League\Fractal\Manager;
use Platform\TNA\Transformers\TNAItemTransformer2;
use League\Fractal\Resource\Collection;
use Platform\TNA\Helpers\TNAHelper;
use Illuminate\Support\Facades\Response;
use Platform\TNA\Transformers\TNAItemTransformer;

class ItemsOrderCalculator 
{
    /**
     * Calculate items order after getting TNA from tnaid
     *
     * @var string $tnaId
     * @var string $type
     * @return jsonarray
     */
    public function calculate($tnaId, $type = null)
    {

        $tna = TNA::find($tnaId);
        return $this->calculateItemsOrder($tna);

        /*
        $result = \DB::select("select getTNA_json('$tnaId')");

        if(is_null(json_decode($result[0]->gettna_json))){
            return [];
        }

        if($type === "array"){
            return $this->sortItemsList(json_decode($result[0]->gettna_json, true), true);
        }
        return $this->sortItemsList(json_decode($result[0]->gettna_json));
         */
    }

    /**
     *  Calculate items order of a given tna
     *
     *  @var object $tna
     *  @return jsonarray
     */
    private function calculateItemsOrder($tna)
    {
        $milestones = TNAItem::where('tna_id', '=', $tna->id)->where('is_milestone', '=', true)->get();
        $milestones = $this->transformCollection($milestones, new TNAItemTransformer, 'tna item');
        $milestones = TNAHelper::sortItemsOrder($milestones);

        foreach($milestones as $key => $milestone) {
            $items = TNAItem::where('tna_id', '=', $tna->id)
                            ->where('dependor_id', '=', $milestone['itemId'])
                            ->get();
            $transformedItems = $this->transformCollection($items, new TNAItemTransformer, 'tna item');
            $transformedItems = TNAHelper::sortItemsOrder($transformedItems);
            $milestones[$key]['nodes'] = $transformedItems;
        }
        return $milestones;
    }

    /**
     * Transform collection using any callback transformer
     *
     * @var Collection $collection
     * @var object $callback
     * @var string $namespace
     * @return Array
     */
    private function transformCollection($collection, $callback, $namespace = 'data')
    {
        $resource = new Collection($collection, $callback, $namespace);

        $rootScope = (new Manager())->createData($resource)->toArray();

        return $rootScope['data'];
    }

}

