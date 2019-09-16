<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

/**
* For Sending email notification of email
*/
class DigestExternalNotificationHandler extends Notifier
{
    public function sendDailyDigest($data, $view, $receiver, $subject)
    {  
        $verifyReceiver = $this->validateSEUser($receiver->id);
        if(is_null($verifyReceiver)){
            return true;
        }
        $data['user'] = $receiver;
        $data['link'] = $this->getHostUrl();
        $this->notifyViaEmail($data, $view, $receiver->email, $subject);
        return true;
    }
}