<?php
namespace Platform\App\RuleCommanding;

use Illuminate\Foundation\Application;
use Platform\App\Exceptions\SeException;




class DefaultRuleBus implements RuleBus
{
    
    protected $app;
    protected $ruleTranslator;
    function __construct(Application $app, RuleTranslator $ruleTranslator)
    {
        $this->app = $app;
        $this->ruleTranslator = $ruleTranslator;
    }

    public function execute($command, $actor, $method)
    {
        if(strtolower($actor) == 'se-bot'){
            $actor = $this->generateSeBot();
        }

        $handlerClass = $this->ruleTranslator->toNotificationHandler($command);
        $validator = $this->ruleTranslator->methodValidator($handlerClass, $method);
        try{
            $emailData = (new $handlerClass)->$method($command, $actor);
        }catch(\Exception $e){
            return $command;
        }
        return $command;
    }

    /**
      * For Sending Notification using cron jobs or queue
      */
    public function generateSeBot()
    {
        $this->actor = new \stdclass();
        $this->actor->display_name = 'no-reply se';
        $this->actor->email = 'no-reply@sourceeasy.com ';
        $this->actor->id = '9GYU5-POHF3-#UYY3-0976';
        return $this->actor;   
    }
}
