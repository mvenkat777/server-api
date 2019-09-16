<?php

use Illuminate\Database\Seeder;
use Platform\TNA\Models\TNA;

class TNADataMigrator extends Seeder
{
	
	function run()
	{
        \DB::beginTransaction();
        $tnaList = TNA::where('created_at', '<', '2016-03-13 23:59:59')->get();
        foreach($tnaList as $tnaKey => $tna){
            echo $tnaKey.' ';
            $itemsOrder = json_decode($tna->items_order);

            if(array_key_exists(count($itemsOrder) - 1, $itemsOrder) && !$itemsOrder[count($itemsOrder) - 1]->isMilestone){
                $itemsOrder[count($itemsOrder) - 1]->isMilestone = true;
                $this->changeMilestone($itemsOrder[count($itemsOrder) - 1]->itemId, true);
            }

            if($tna->id == 'e6160276-6dcd-407a-ba99-21a3bb00933f'){
                $this->saveItem($itemsOrder[0]->itemId, $itemsOrder[0]->nodes[1]->itemId);
                $this->saveItem($itemsOrder[0]->nodes[0]->itemId, $itemsOrder[0]->nodes[1]->itemId);
                $this->saveItem($itemsOrder[0]->nodes[1]->itemId, null);
                $this->saveItem($itemsOrder[1]->itemId, $itemsOrder[2]->itemId);
            }
            else {
                $previousMilestoneKey = null;
                foreach($itemsOrder as $itemKey => $item){
                    if($item->isMilestone){
                        if(!is_null($previousMilestoneKey)){
                            $this->migrateOneSection($itemKey, $previousMilestoneKey, $itemsOrder);
                        } else {
                            if(array_key_exists($itemKey - 1, $itemsOrder)){
                                $this->migrateOneSection($itemKey, 0, $itemsOrder);
                            }
                        }

                        $previousMilestoneKey = $itemKey;
                    } 
                }
            }
            $tna->items_order = json_encode((new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tna->id));
            $tna->save();
        }
        \DB::commit();
        echo "success";
    }

    function migrateOneSection($to, $from, $itemsOrder){

        for($i = $from; $i < $to; $i++){
            if($itemsOrder[$i]->isMilestone){ //for first item if milestone
                continue;
            }

            if(!empty($itemsOrder[$i]->nodes)){
                $prevNodeMilestoneKey = null;
                foreach($itemsOrder[$i]->nodes as $nodeKey => $node){
                    $this->saveItem($node->itemId, $itemsOrder[$to]->itemId, true);
                }
            }
            $this->saveItem($itemsOrder[$i]->itemId, $itemsOrder[$to]->itemId);
        }
    }

    function saveItem($itemId, $dependorId, $isParallel = false)
    {
        if($isParallel){
            $isParallel = 't';
        } else {
            $isParallel = 'f';
        }

        if($dependorId == ''){
            return \DB::statement("UPDATE tna_items SET dependor_id=null, is_parallel='$isParallel' where id='$itemId'");
        }

        \DB::statement("UPDATE tna_items SET dependor_id='$dependorId', is_parallel='$isParallel' where id='$itemId'");
    }

    function changeMilestone($itemId, $isMilestone)
    {
        if($isMilestone){
            $isMilestone = 't';
        } else {
            $isMilestone = 'f';
        }

        \DB::statement("UPDATE tna_items SET is_milestone='$isMilestone' where id='$itemId'");
    }

}
