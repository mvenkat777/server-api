<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

use Illuminate\Support\Facades\Mail;
use Exception;
use Platform\App\Exceptions\SeException;

/**
* Notification Abstract Class
*/
abstract class Notifier
{
    public function notifyViaEmail($data, $view, $receiver, $subject)
    { 
        /**
         * 107.170.223.191 & 45.55.2.53 -- is our client server address
         * We are ignoring to send notification from that server
         */
        if(getenv('APP_ENV') == 'local'){
            return true;
        }
        if(getenv('APP_ENV') == 'staging'){
            $subject = '(From Staging) ' . $subject;
        }
        try {
            Mail::send($view, ['data' => $data], function ($message) use ($receiver, $subject) {
                $message->to($receiver)->subject($subject);
            });

        } catch (Exception $e) {
            throw new SeException('Failed To Send Mail', '500');
        }
    }

    public function getHostUrl()
    {
        if(getenv('APP_ENV') == 'staging'){
            return 'http://platform.sourc.in/#/';
        } elseif(getenv('APP_ENV') == 'production'){
            return 'http://platform.sourceeasy.com/#/';
        } else {
            return 'http://platform.dev/#/';
        }
    }

    public function getModelVariables($model)
    {
        
        if(isset($model->relation)){
            $data['relation'] = $model->relation;
        } 
        if(isset($model->verbs)){
            $data['verbs'] = $model->verbs;
        }
        if(isset($model->values)){
            $data['values'] = $model->values;
        }
        return $data;
    }

    public function user($userId)
    {
        return (new $this->userTransformer)->transform(\App\User::find($userId));
    }

    public function validateSEUser($user)
    {
        return \App\User::where('id', $user)
                            ->orWhere('email', $user)
                            ->where('se', true)
                            ->where('is_banned', true)
                            ->where('is_active', true)
                            ->first();
    }
}