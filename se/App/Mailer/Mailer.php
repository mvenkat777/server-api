<?php

namespace Platform\App\Mailer;

use Illuminate\Support\Facades\Mail;
use Exception;
use Platform\App\Exceptions\SeException;
use Session;

abstract class Mailer
{
    /**
     * @param $user
     * @param $subject
     * @param $view
     * @param $data
     */
    public function sendTo($user, $subject, $view, $data = [], $emailContent = '', $entity = '')
    {
        if(isset($data->emailSubject)) {
            $subject = $data->emailSubject;
        }
        Session::forget('content');
        $this->setEmailContent($emailContent);
        $mail = view($view, ['data' => $data])->render();
        \File::put(base_path().'/resources/views/emails/'.strtolower($entity).'/send.blade.php', $mail);
        try {
            Mail::send('emails.'.strtolower($entity).'.send', ['data' => $data], function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });
        } catch (Exception $e) {
            Session::forget('content');
            return;
        }
        Session::forget('content');
        return;
    }

    public function sendWithAttachment($user, $subject, $view, $data = [], $attachment = [])
    {
        Mail::send($view, ['data' => $data, 'name' => $user['display_name']], function ($message) use ($user, $subject, $attachment) {
            $message->to($user->email)->subject($subject);
            $totalAttachement = count($attachment);
            while ($totalAttachement > 0) {
                --$totalAttachement;
                $message->attach($attachment[$totalAttachement]);
            }
        });
    }

    public function sendToWithAttachment($user, $subject, $view, $data = [], $attachment = [], $mailContent = null, $entity = null)
    {
        Session::forget('content');
        $this->setEmailContent($mailContent);
        $mail = view($view)->render();
        \File::put(base_path().'/resources/views/emails/'.strtolower($entity).'/send.blade.php', $mail);
        try {
            Mail::send('emails.'.strtolower($entity).'.send', ['data' => $data], function ($message) use ($user, $subject, $attachment) {
                $message->to($user->email)->subject($subject);
                $totalAttachement = count($attachment);
                while ($totalAttachement > 0) {
                    --$totalAttachement;
                    $message->attach($attachment[$totalAttachement]);
                }
            });

        } catch (Exception $e) {
            Session::forget('content');
            return;
        }
        Session::forget('content');
        return;
    }

    public function sendToSharedNote($user, $subject, $view, $data = [])
    {
        if(getenv('APP_ENV') == 'local'){
            return true;
        }
        try {

            Mail::send($view, ['data' => $data], function ($message) use ($user, $subject) {
                $message->to($user)->subject($subject);
            });

        } catch (Exception $e) {
            throw new SeException('Failed To Send Mail', '500');
        }
    }

    public function sendToUser($user, $subject, $view, $data = [])
    {
        if(getenv('APP_ENV') == 'local'){
            return true;
        }
        try {
            Mail::send($view, ['data' => $data], function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });

        } catch (Exception $e) {
            throw new SeException('Failed To Send Mail', '500');
        }
    }

    public function setEmailContent($emailContent){
        Session::put('content', $emailContent);
        return ;
    }
}
