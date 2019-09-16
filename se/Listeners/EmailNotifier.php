<?php
namespace Platform\Listeners;

use Platform\App\Events\EventListener;
use Platform\Authentication\CustomerWasCreated;
use Platform\Authentication\Events\UserForgotPassword;
use Platform\Notes\Events\CommentWasAdded;
use Platform\Notes\Mailer\NoteMailer;
use Platform\Notes\Events\NoteWasShared;
use Platform\Payment\Mailer\PaymentMailer;
use Platform\Payment\PaymentLinkWasCreated;
use Platform\Payment\RequestWasFailed;
use Platform\Payment\RequestWasSuccessful;
use Platform\Tasks\Events\SendMailForTaskOwner;
use Platform\Tasks\Events\SendMailWithAttachements;
use Platform\Tasks\Events\SendMailWithAttachementsAndComments;
use Platform\Tasks\Events\TaskWasClosed;
use Platform\Tasks\Events\TaskWasCompleted;
use Platform\Tasks\Events\TaskWasCreated;
use Platform\Tasks\Events\TaskWasSubmitted;
use Platform\Tasks\Mailer\TaskMailer;
use Platform\Users\Mailer\UserMailer;
use Platform\Users\UserWasCreated;

class EmailNotifier extends EventListener{

    protected $mailer;

    protected $taskMailer;

    protected $paymentMailer;

    protected $noteMailer;

    function __construct (UserMailer $mailer,
                        PaymentMailer $paymentMailer,
                        TaskMailer $taskMailer,
                        NoteMailer $noteMailer)
    {
        $this->mailer = $mailer;
        $this->paymentMailer = $paymentMailer;
        $this->taskMailer = $taskMailer;
        $this->noteMailer = $noteMailer;
    }


    public function whenUserWasCreated(UserWasCreated $event)
    {
        $appName = "";

        if(is_null($event->command->app)){
            $appName = "http://dashboard.sourceeasy.com/#/auth/verify/";
        }
        else if(strpos($event->command->app, 'techpack')){
            $appName = $event->command->app."/app/#/auth/verify/";
        }
        else{
            $appName = $event->command->app."/#/auth/verify/";
        }

        $this->mailer->verifyUser($event->command, ['confirmationCode'=>$event->confirmationCode, 'appName' => $appName]);
    }

    public function whenUserForgotPassword(UserForgotPassword $event)
    {
        $appName = "";

        if(is_null($event->command->app))
            $appName = "http://platform.sourceeasy.com/#/auth/reset?email=";
        else if(strpos($event->command->app, 'techpack'))
            $appName = $event->command->app."/app/#/auth/reset?email=";
        else
            $appName = $event->command->app."/#/auth/reset?email=";

    	$this->mailer->resetPassword($event->command, ['token'=>$event->token, 'email'=>$event->command->email, 'appName'=>$appName]);
    }

    public function whenPaymentLinkWasCreated(PaymentLinkWasCreated $event)
    {
        $this->paymentMailer->paymentLink($event->command, ['link'=>'http://payments.sourceeasy.com/?paymentId='.$event->command->productLink ,
                                                            'amount'=>$event->command->amount,
                                                            'productName'=>$event->command->productName,
                                                            'created_at'=>$event->command->created_at,
                                                            'receiptNumber'=>$event->command->id,
                                                            'displayName' => \Auth::user()->display_name,
                                                            'name'=>is_null($event->command->name)? ($event->command->userObject['displayName']):($event->command->name)]);
    }

    public function whenRequestWasSuccessful(RequestWasSuccessful $event)
    {
        $this->paymentMailer->paymentSuccess($event->command, ['paymentId'=>$event->command->id,
                                                                'amount'=>$event->command->amount,
                                                                'receiptNumber'=>$event->command->id,
                                                                'productName'=>$event->command->productName,
                                                                'created_at'=>$event->command->created_at,
                                                                'updated_at'=>$event->command->updated_at,
                                                                'senderName'=>$event->command->senderName,
                                                                'senderEmail'=>$event->command->senderEmail,
                                                                'name'=>is_null($event->command->name)? ($event->command->userObject['displayName']):($event->command->name)]);
    }

    public function whenRequestWasFailed(RequestWasFailed $event)
    {
        $this->paymentMailer->paymentFailed($event->command, ['paymentId'=>$event->command->id,
                                                            'amount'=>$event->command->amount,
                                                            'receiptNumber'=>$event->command->id,
                                                            'productName'=>$event->command->product_name,
                                                            'created_at'=>$event->command->created_at,
                                                            'senderName'=>$event->command->sender_name,
                                                            'senderEmail'=>$event->command->sender_email,
                                                            'name'=>is_null($event->command->name)? ($event->command->user_object['displayName']):($event->command->name)]);
    }

    public function whenTaskWasCreated(TaskWasCreated $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $this->taskMailer->taskWasCreated($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignersName' => $event->task->creator->display_name,
                                            'name' => $event->task->title,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'link' => $origin.'/#/tasks/list?q=assigned'
                                        ]);
    }

    public function whenTaskWasSubmitted(TaskWasSubmitted $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $this->taskMailer->taskWasSubmitted($event->task->creator,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignersName' => $event->task->creator->display_name,
                                            'name' => $event->task->title,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'link' => $origin.'/#/tasks/list?q=reviewd'
                                        ]);
    }

    public function whenTaskWasCompleted(TaskWasCompleted $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $this->taskMailer->taskWasArchived($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignerName' => $event->task->creator->display_name,
                                            'name' => $event->task->title,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'link' => $origin.'/#/tasks/list?q=archived',
                                            'taskStatus' => $event->task->status->status,
                                            'note' => $event->task->notes
                                        ]);
    }

    public function whenTaskWasClosed(TaskWasClosed $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $this->taskMailer->taskWasArchived($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignerName' => $event->task->creator->display_name,
                                            'name' => $event->task->title,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'link' => $origin.'/#/tasks/list?q=archived',
                                            'taskStatus' => $event->task->status->status,
                                            'note' => $event->task->notes
                                        ]);
    }

    public function whenSendMailForTaskOwner(SendMailForTaskOwner $event)
    {
        $this->taskMailer->sendMailForTaskOwner($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignerName' => $event->task->creator->display_name,
                                            'taskName' => $event->task->title,
                                            'taskDescription' => $event->task->description,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'taskStatus' => $event->task->status->status,
                                            'note' => $event->task->notes
                                        ]);
    }

    public function whenSendMailWithAttachements(SendMailWithAttachements $event)
    {
        $this->taskMailer->sendMailWithAttachements($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignerName' => $event->task->creator->display_name,
                                            'taskName' => $event->task->title,
                                            'taskDescription' => $event->task->description,
                                            'attachments' => $event->task->attachments,
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'taskStatus' => $event->task->status->status,
                                            'note' => $event->task->notes
                                        ]);
    }

    public function whenSendMailWithAttachementsAndComments(SendMailWithAttachementsAndComments $event)
    {
        $this->taskMailer->sendMailWithAttachementsAndComments($event->task->assignee,
                                        [
                                            'taskId' => $event->task->id,
                                            'assigneName' => $event->task->assignee->display_name,
                                            'assignerName' => $event->task->creator->display_name,
                                            'taskName' => $event->task->title,
                                            'taskDescription' => $event->task->description,
                                            'attachments' => $event->task->attachments,
                                            'comments' => json_decode($event->task->comments),
                                            'created_at' => $event->task->created_at->toDateTimeString(),
                                            'taskStatus' => $event->task->status->status,
                                            'note' => $event->task->notes
                                        ]);
    }

    public function whenNoteWasShared(NoteWasShared $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $link = $origin.'/notes/'.$event->shareBy->displayName.'/'.$event->note->id.'/'.$event->note->title;
        $this->noteMailer->shareNoteMail($event->email, ['noteLink' => $link,
                                                         'sharedBy' => $event->shareBy->displayName
                                                        ]);

    }

    public function whenCommentWasAdded(CommentWasAdded $event)
    {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $link = $origin.'/notes/'.$event->commentedBy->displayName.'/'.$event->note->id.'/'.$event->note->title;
        $this->noteMailer->CommentNoteMail($event->email,
                                                [   'noteLink' => $link,
                                                    'commentedBy' => $event->commentedBy->displayName
                                                ]
                                            );
    }
}
