<?php

namespace Platform\TNA\Helpers;

use Platform\TNA\Helpers\TaskDispatcher;
use Platform\App\Wrappers\GoogleCalender;

class TNAPublisher 
{
    protected $taskDispatcher;

    protected $googleCalender;

    function __construct(TaskDispatcher $taskDispatcher)
    {
        $this->taskDispatcher = $taskDispatcher;
        $this->googleCalender = new GoogleCalender;
    }

    public function publish($tna)
    {
        $itemsOrder = is_null(json_decode($tna->items_order)) ? [] : json_decode($tna->items_order);
        //$itemsOrder = json_decode($tna->items_order);
        $items_order = $itemsOrder;

        foreach($itemsOrder as $key => $item) {
//            echo $key;
            if(count($item->nodes) > 0) {
                foreach($item->nodes as $node) {
                    $items_order = $this->dispatchItem($node, $itemsOrder);
                }
            }
            $items_order = $this->dispatchItem($item, $itemsOrder);
        }

        $tna->items_order = json_encode($items_order);
        //$tna->save();
        return $tna;
    }

    private function dispatchItem($item, $itemsOrder)
    {
        if(!$item->isDispatched) {
//            echo "dispatching\n";
//            try {
                return $this->taskDispatcher->dispatch($item);
                //$this->addToGoogleCalender($item);
            /*
            } catch(\Exception $e) {
            }
             */
        }
        return $itemsOrder;
    }

    private function addToGoogleCalender($item)
    {
        echo $item->title;
        echo $item->representor->email;
        $location = 'Hyderabad';
        $startDate = '2016-05-25T19:30:00+08:00'; 
        $endDate = '2016-05-25T19:30:00+08:00'; 
        $this->googleCalender->setCalender($item->representor->email, $item->title, $location, $startDate, $endDate);
    }
}
