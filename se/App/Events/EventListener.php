<?php

namespace Platform\App\Events;

use ReflectionClass;

/**
 * Class EventListener
 * @package Platform\Events
 */
class EventListener
{
    /**
     * @param $event
     * @return mixed
     */
    public function handle($event)
    {
        $eventName = $this->getEventName($event);

        if ($this->listenerIsRegistered($eventName)) {
            return call_user_func([$this, "when".$eventName], $event);
        }
    }

    /**
     * @param $event
     * @return string
     */
    protected function getEventName($event)
    {
        return (new ReflectionClass($event))->getShortName();
    }

    /**
     * @param $eventName
     * @return bool
     */
    protected function listenerIsRegistered($eventName)
    {
        $method = "when{$eventName}";
        return method_exists($this, $method);
    }
}
