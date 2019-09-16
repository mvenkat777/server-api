<?php

namespace Platform\App\Events;

use Illuminate\Events\Dispatcher;
use Illuminate\Log\Writer;
use Platform\App\Wrappers\SlackWrapper;

class EventDispatcher
{
    protected $event;
    protected $log;
    protected $salck;

    public function __construct(Dispatcher $event, Writer $log)
    {
        $this->event = $event;
        $this->log = $log;
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            $eventName =  $this->getEventName($event);
            $this->event->fire($eventName, $event);
            (new SlackWrapper)->send($this->getEvent($eventName));
            $this->log->info("Event {$eventName} was fired");
        }
    }

    protected function getEventName($event)
    {
        return str_replace('\\', '.', get_class($event));
    }

    protected function getEvent($event)
    {
        $event = explode('.', $event);
        return $event[sizeof($event) - 1];
    }
}
