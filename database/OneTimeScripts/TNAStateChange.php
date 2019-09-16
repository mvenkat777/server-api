<?php

use Illuminate\Database\Seeder;
use Platform\TNA\Models\TNA;
use Platform\TNA\Helpers\TNAHelper;

class TNAStateChange extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::beginTransaction();
        $activeTNA = TNA::where('tna_state_id', TNAHelper::getTNAStateId('active'))->get();
        $pausedTNA = TNA::where('tna_state_id', TNAHelper::getTNAStateId('paused'))->get();
        echo "Before Update\n";
        echo "Active TNA : ".count($activeTNA)." Paused TNA : ".count($pausedTNA)."\n";
        TNA::where('tna_state_id', TNAHelper::getTNAStateId('active'))
            ->update([
                'tna_state_id' => TNAHelper::getTNAStateId('paused')
            ]);
        $activeTNA = TNA::where('tna_state_id', TNAHelper::getTNAStateId('active'))->get();
        $pausedTNA = TNA::where('tna_state_id', TNAHelper::getTNAStateId('paused'))->get();
        echo "After Update\n";
        echo "Active TNA : ".count($activeTNA)." Paused TNA : ".count($pausedTNA)."\n";
        \DB::commit();
    }
}
