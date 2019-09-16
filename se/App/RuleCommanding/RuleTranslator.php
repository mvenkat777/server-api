<?php
namespace Platform\App\RuleCommanding;

use Platform\App\Exceptions\SeException;
class RuleTranslator
{
    public function toNotificationHandler($command)
    {
        $class = get_class($command);
        $collection = '\Illuminate\Database\Eloquent\Collection';
        if((new $class) instanceof $collection){
            $class = get_class($command[0]);
        }
        $handler = str_replace('Notification', 'Handlers\Notification', $class) . 'ExternalNotificationHandler';
        if(strpos($handler, 'App\\') !== false){
            $class = str_replace('App\\', '\Platform\App\RuleCommanding\ExternalNotification\\', $handler);
        } elseif(strpos($handler, 'Platform\\') !== false){
            $class = str_replace('Platform\TNA\Models\\', '\Platform\App\RuleCommanding\ExternalNotification\\', $handler);
        } elseif(strpos($handler, 'Illuminate\\') !== false){

        }
        if (! class_exists($class)) {
            $message = "Notification handler class [$class] does not exisit.";

            throw new SeException($message, '404', '9001404');
        }
        return $class;
    }

    public function methodValidator($class, $method)
    {
        $new = new $class;
        $ifExists = method_exists($new, lcfirst($method));
        if(!$ifExists){
            throw new SeException("Method ".[lcfirst($method)]." does not exists", 404, '9002404');
        }
        return lcfirst($method);
    }
}

