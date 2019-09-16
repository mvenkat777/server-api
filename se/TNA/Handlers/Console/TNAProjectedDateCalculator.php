<?php

namespace Platform\TNA\Handlers\Console;

use Carbon\Carbon;
use Platform\TNA\Handlers\Console\TNAHealthCalculator;
use Platform\TNA\Helpers\TNAHelper;

class TNAProjectedDateCalculator 
{
	/**
	 * Calculate Projected date of TNA
	 * 
	 * @param TNA Model $tna 
	 * @return TNA Model      
	 */
	public function calculate($tna, $itemsOrder = null)
	{
        if(is_null($itemsOrder)) {
            $itemsOrder = json_decode($tna->items_order, true);
        } else {
            $itemsOrder = json_decode(json_encode($itemsOrder), true);
        }

		if(count($itemsOrder) < 1){
			$tna->projected_date = $tna->start_date;
		} else {
			$tna->projected_date = $this->calculateProjectedDate($tna, $itemsOrder);
            /*
			$tna->projected_date = isset($itemsOrder[count($itemsOrder) - 1]->projectedDate) 
					? $itemsOrder[count($itemsOrder) - 1]->projectedDate
					: $itemsOrder[count($itemsOrder) - 1]->plannedDate;
             */
		}

		$tna = (new TNAHealthCalculator)->calculate($tna);
		//$tna->save();
		return $tna;
	}

	/**
	 * Calculate Projected Date according to itemsOrder
	 * 
	 * @param  TNA Model $tna        
	 * @param  jsonarray $itemsOrder 
	 * @return DATE             
	 */
	private function calculateProjectedDate($tna, array $itemsOrder)
	{
        $itemsOrder = array_column($itemsOrder, 'plannedDate');
        return max($itemsOrder);
	}

	/**
	 * Calculate Projected Date according to itemsOrder
	 * 
	 * @param  TNA Model $tna        
	 * @param  jsonarray $itemsOrder 
	 * @return DATE             
	 */
	private function calculateProjectedDate1($tna, $itemsOrder)
	{
		$startCalculationDate = $this->getStartCalculationDate($tna);
		$lastItemDate = $this->getLastItemPlannedDate($itemsOrder);
		$diffInDates = TNAHelper::diffInDates($startCalculationDate, $lastItemDate);
		$totalDelta = $this->getTotalDelta($itemsOrder);
		return $startCalculationDate->addDays($diffInDates + $totalDelta);
	}

	/**
	 * Get Start Calculation Date according to TNA status
	 * 
	 * @param  TNA Model $tna 
	 * @return DATE      
	 */
	private function getStartCalculationDate($tna)
	{
		$tnaStatus = $tna->state->state;
		if($tnaStatus == 'draft')
			return Carbon::parse($tna->start_date);
		else
			return Carbon::parse($tna->published_date);
	}

	/**
	 * Get Planned date of Last TNA item
	 * 
	 * @param  jsonarray $itemsOrder 
	 * @return DATE             
	 */
	private function getLastItemPlannedDate($itemsOrder)
	{
		return Carbon::parse($itemsOrder[count($itemsOrder) - 1]->plannedDate);
	}

	/**
	 * Get total value of delta
	 * 
	 * @param  jsonarray $itemsOrder 
	 * @return integer             
	 */
	private function getTotalDelta($itemsOrder)
	{
		$totalDelta = 0;
		foreach ($itemsOrder as $key => $itemOrder) {
			if(!is_null($itemOrder->delta)){
				$totalDelta = $totalDelta + intval($itemOrder->delta);
			}
			else{
				break;
			}
		}
		return $totalDelta;
	}

}
