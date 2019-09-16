<?php

namespace Platform\TNA\Transformers;

use League\Fractal\TransformerAbstract;
use Platform\TNA\Helpers\TNAHelper;
use Carbon\Carbon;
use League\Fractal\Manager;

class TNATransformerAbstract extends TransformerAbstract 
{
	public function __construct()
	{
		$this->manager = new Manager();
	}

    public function calculateProjectedDate($tnaItem)
    {
        if($tnaItem->isFromCreate) {
            $this->projectedDate = $tnaItem->planned_date;
        } else {

            if(!is_null($tnaItem->dependor_id)){
                $foundKey = array_search(
                    $tnaItem->dependor_id, 
                    array_column(json_decode($tnaItem->tna->items_order, true), 'itemId')
                );
                
                $itemsOrder = json_decode($tnaItem->tna->items_order);

                $nodeKey = array_search(
                    $tnaItem->id, 
                    array_column(json_decode(json_encode($itemsOrder[$foundKey - 1]->nodes), true), 'itemId')
                );
                // dd($nodeKey);
                if($itemsOrder[$foundKey - 1]->deltaSign){ //substractDayFromDate
                    $this->projectedDate = TNAHelper::substractDayFromDate(
                        $tnaItem->planned_date, 
                        intval(preg_replace(['/\+/', '/-/'], '', $itemsOrder[$foundKey - 1]->delta))
                    )->toDateTimeString();
                    // dd($itemsOrder[$foundKey - 1]->nodes[$nodeKey]->plannedDate, 
                    //     $itemsOrder[$foundKey - 1]->delta, 
                    //     $this->projectedDate,
                    //     $this->calculateDeltaWithSign($tnaItem));
                } else {
                    // dd('add');
                    $this->projectedDate = TNAHelper::addDayToDate(
                        $itemsOrder[$foundKey - 1]->nodes[$nodeKey]->plannedDate, 
                        intval(preg_replace(['/\+/', '/-/'], '', $itemsOrder[$foundKey - 1]->delta))
                    )->toDateTimeString();
                }
            } else {
                $foundKey = array_search(
                    $tnaItem->id, 
                    array_column(json_decode($tnaItem->tna->items_order, true), 'itemId'));
            
                $itemsOrder = json_decode($tnaItem->tna->items_order);
                $tna = $tnaItem->tna;
                // dd($itemsOrder[1]);
                if(count($itemsOrder) === 0 || $foundKey === 0) {
                    $this->projectedDate = $tnaItem->planned_date;
                } else {
                    // dd($foundKey, array_key_exists($foundKey - 1, $itemsOrder), $itemsOrder[$foundKey - 1]);
                    if(array_key_exists($foundKey - 1, $itemsOrder)){
                        if(is_null($itemsOrder[$foundKey - 1]->delta)){
                            $itemsOrder[$foundKey - 1]->delta = 0;
                        }
                        if($itemsOrder[$foundKey - 1]->deltaSign){ //substractDayFromDate

                            if($itemsOrder[$foundKey - 1]->delta === 0){
                                // echo $itemsOrder[$foundKey - 1]->title.' -> ';
                                // echo $itemsOrder[$foundKey - 1]->title.'<br>';
                                $this->projectedDate = $tnaItem->planned_date;
                            } else {
                                $this->projectedDate = TNAHelper::substractDayFromDate(
                                    $tnaItem->planned_date, 
                                    intval(preg_replace(['/\+/', '/-/'], '', $itemsOrder[$foundKey - 1]->delta))
                                )->toDateTimeString();
                                // if($tnaItem->id === '70532ec8-2a37-4aa7-a99b-6f2c8afebe81'){
                                //     dd($tnaItem->planned_date,
                                //         $itemsOrder[$foundKey - 1]->delta,
                                //         $this->projectedDate);
                                // }
                            }
                        } else {
                            // if($tnaItem->id === '70532ec8-2a37-4aa7-a99b-6f2c8afebe81'){
                            //         dd('dddd');
                            //     }
                            $this->projectedDate = TNAHelper::addDayToDate(
                                $itemsOrder[$foundKey]->plannedDate, 
                                intval(preg_replace(['/\+/', '/-/'], '', $itemsOrder[$foundKey - 1]->delta))
                            )->toDateTimeString();
                        }
                    } else {
                        // if($tnaItem->id === '70532ec8-2a37-4aa7-a99b-6f2c8afebe81'){
                        //             dd('else');
                        //         }
                        $this->projectedDate = $tnaItem->planned_date;
                    }
                }
            }
        }

        // if($tnaItem->id === '70532ec8-2a37-4aa7-a99b-6f2c8afebe81'){
        //     dd($this->projectedDate, $tnaItem->planned_date);
        // }
        $this->projectedDate = is_object($this->projectedDate) ? $this->projectedDate->toDateTimeString() : $this->projectedDate;
    }

    public function calculateDelta($tnaItem)
    {
        $this->calculateProjectedDate($tnaItem);
        if($tnaItem->isFromCreate){
            $this->delta = 0;
            $this->deltaSign = true;
            return $this->delta;
        }
		if($this->getTnaItemStatus($tnaItem) === 'closed') {
			$this->delta = TNAHelper::diffInDates($tnaItem->planned_date, $tnaItem->actual_date);
            $this->deltaSign = $this->calculateDeltaSign($tnaItem->planned_date, $tnaItem->actual_date);
		} else { 
            // echo $tnaitem->planned_date.' ';
            // echo $this->projecteddate.' = ';
            $this->delta = TNAHelper::diffInDates($tnaItem->planned_date, $this->projectedDate);
            $this->deltaSign = $this->calculateDeltaSign($tnaItem->planned_date, $this->projectedDate);
        }
        // echo $this->delta.'<br>';
        return $this->delta;
    } 

    public function calculateDeltaSign($date1, $date2)
    {
        $date1 = Carbon::parse($date1);
        $date2 = Carbon::parse($date2);
        if($date1 < $date2) {
            return false;
        }
        return true;

    }

    public function calculateDeltaWithSign()
    {
        if($this->deltaSign && $this->delta == 0){
            return ''.$this->delta;
        } elseif ($this->deltaSign && $this->delta !== 0) {
            return '-'.$this->delta;
        } else {
            return '+'.$this->delta;
        }

    }

	public function isItemCompleted($tnaItem)
	{
		return is_null($tnaItem->is_completed) ? false : $tnaItem->is_completed;
	}

	public function isItemDispatched($tnaItem)
	{
		return is_null($tnaItem->is_dispatched) ? false : $tnaItem->is_dispatched;
	}

	public function getTNAItemStatus($tnaItem)
	{
		$isCompleted = $this->isItemCompleted($tnaItem);
		$isDispatched = $this->isItemDispatched($tnaItem);

		if($isDispatched && !$isCompleted)
			return 'active';
		elseif ($isCompleted && $isDispatched)
			return 'closed';
		else
			return 'pending';
	}
}
