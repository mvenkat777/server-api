<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

use App\Style;

/**
* For Sending email notification of email
*/
class TaskExternalNotificationHandler extends Notifier
{
    public $userTransformer = 'Platform\Users\Transformers\MetaUserTransformer';
    
    public function createNewTask($task, $actor)
    {

        $valDefinition = $this->updateCollection($task);
        $valDefinition->creator_id = $this->user($task->creator_id);
        $valDefinition->assignee_id = $this->user($task->assignee_id);
        if($valDefinition->assignee_id['email'] == $actor->email){
            return $task;
        }
        $valDefinition->link = $this->getHostUrl().'tasks/'.$valDefinition->id;
        $extraData = $this->getRespectiveLineAndCustomer($task);
        $valDefinition->customer = $extraData['customer'];
        $valDefinition->line = $extraData['line'];
        $subject = $valDefinition->creator_id['displayName'].'('.$valDefinition->creator_id['email'].') has assigned you a '.$valDefinition->priority_id.' priority task : '.$valDefinition->title;
        $receiver = [$valDefinition->assignee_id['email']];
        $this->notifyViaEmail($valDefinition, 'emails.tasks.CreateNewTask', $receiver, $subject);
        return $task;
    }

    public function submitTask($task, $actor)
    {
        $valDefinition = $this->updateCollection($task);
        $valDefinition->creator_id = $this->user($task->creator_id);
        $valDefinition->assignee_id = $this->user($task->assignee_id);
        if($valDefinition->creator_id['email'] == $actor->email){
            return $task;
        }
        $valDefinition->link = $this->getHostUrl().'tasks/'.$valDefinition->id;
        $extraData = $this->getRespectiveLineAndCustomer($task);
        $valDefinition->customer = $extraData['customer'];
        $valDefinition->line = $extraData['line'];
        $subject = $valDefinition->assignee_id['displayName'].'('.$valDefinition->assignee_id['email'].') has submitted a '.$valDefinition->priority_id.' priority task assigned by you : '.$valDefinition->title;
        $receiver = [$valDefinition->creator_id['email']];
         $this->notifyViaEmail($valDefinition, 'emails.tasks.TaskWasSubmitted', $receiver, $subject);
        return $task;
    }
  public function completeTask($task, $actor) 
  {
        $valDefinition = $this->updateCollection($task);
        $valDefinition->creator_id = $this->user($task->creator_id);
        $valDefinition->assignee_id = $this->user($task->assignee_id);
        if($valDefinition->assignee_id['email'] == $actor->email){
            return $task;
        }
        $valDefinition->link = $this->getHostUrl().'tasks/'.$valDefinition->id;
        $extraData = $this->getRespectiveLineAndCustomer($task);
        $valDefinition->customer = $extraData['customer'];
        $valDefinition->line = $extraData['line'];
        $subject = $valDefinition->assignee_id['displayName'].'('.$valDefinition->assignee_id['email'].') has completed a '.$valDefinition->priority_id.' priority task : '.$valDefinition->title;
        $receiver = [$valDefinition->creator_id['email']];
        $this->notifyViaEmail($valDefinition, 'emails.tasks.TaskWasCompleted', $receiver, $subject);
        $receiver = [$valDefinition->assignee_id['email']];
        $subject = $valDefinition->creator_id['displayName'].'('.$valDefinition->creator_id['email'].') has approved '.$valDefinition->title;
        $this->notifyViaEmail($valDefinition, 'emails.tasks.TaskWasApproved', $receiver, $subject);
        return $task;
   }
   public function closeTask($task, $actor) 
     {  
        try{
        $valDefinition = $this->updateCollection($task);
        $valDefinition->creator_id = $this->user($task->creator_id);
        $valDefinition->assignee_id = $this->user($task->assignee_id);
        if($valDefinition->assignee_id['email'] == $actor->email){
            return $task;
        }
        $valDefinition->link = $this->getHostUrl().'tasks/'.$valDefinition->id;
        $extraData = $this->getRespectiveLineAndCustomer($task);
        $valDefinition->customer = $extraData['customer'];
        $valDefinition->line = $extraData['line'];
        $subject = $valDefinition->creator_id['displayName'].'('.$valDefinition->creator_id['email'].') has rejected '.$valDefinition->priority_id.' priority task : '.$valDefinition->title;
        $valDefinition->actorName = $actor->display_name;
        $valDefinition->actorEmail= $actor->email;
        $receiver = [$valDefinition->assignee_id['email']];
        $this->notifyViaEmail($valDefinition, 'emails.tasks.TaskWasClosed', $receiver, $subject);
    }catch(\Exception $e){
        dd($e->getMessage());
    }
        return $task;
    }

    public function reassignTask($task, $actor)
    {
        $valDefinition = $this->updateCollection($task);
        $valDefinition->creator_id = $this->user($task->creator_id);
        $valDefinition->assignee_id = $this->user($task->assignee_id);
        if($valDefinition->assignee_id['email'] == $actor->email){
            return $task;
        }
        $valDefinition->link = $this->getHostUrl().'tasks/'.$valDefinition->id;
        $extraData = $this->getRespectiveLineAndCustomer($task);
        $valDefinition->customer = $extraData['customer'];
        $valDefinition->line = $extraData['line'];
        $subject = $valDefinition->creator_id['displayName'].'('.$valDefinition->creator_id['email'].') has re-assigned you a '.$valDefinition->priority_id.' priority task : '.$valDefinition->title;
        $valDefinition->actorName = $actor->display_name;
        $receiver = [$valDefinition->assignee_id['email']];
        $this->notifyViaEmail($valDefinition, 'emails.tasks.TaskWasReassigned', $receiver, $subject);
        return $task;
    }

    public function pastDueDateReminder($task, $actor)
    {
        $taskPerUser = [];
        foreach ($task as $key => $value) {
            if(!isset($taskPerUser[$value->assignee_id])){
                $taskPerUser[$value->assignee_id] = [];
            }
            array_push($taskPerUser[$value->assignee_id], $value->toArray());
        }
        foreach ($taskPerUser as $key => $value) {
            $valDefinition['link'] = $this->getHostUrl().'tasks/';
            $valDefinition['actor'] = $this->user($key);
            $subject = 'The due date is past for '.count($value).' task(s)';
            $valDefinition['task'] = $value;
            $this->notifyViaEmail($valDefinition, 'emails.tasks.PastDueDateReminder', [$valDefinition['actor']['email']], $subject);
        }
        return 'Sent Successfully';
    }

    public function getRespectiveLineAndCustomer($task)
    {
        $tna = null;
        $style = null;
        $customer = null;
        $line = null;
        $isMilestone = false;
        if(!is_null($task->tnaItem) && !is_null($task->tnaItem->tna)) {
            $isMilestone = $task->tnaItem->is_milestone;
            $tna = $task->tnaItem->tna;
            $customer = $tna->customer;
            $style = Style::where('tna_id', $tna->id)->first();
            if(!is_null($style)) {
                $line = $style->line;
                $line = [
                    'id' => $line->id,
                    'name' => $line->name,
                    'code' => $line->code
                ];
                $style = [
                    'id' => $style->id,
                    'code' => $style->code,
                    'name' => $style->name
                ];
            }
            $customer = [
                'customerId' => (string)$customer->id,
                'code' => (string)$customer->code,
                'name' => (string)$customer->name
            ];
            $tna = [
                'id' => $tna->id,
                'title' => $tna->title
            ];
        }
        return ['customer' => $customer, 'line' => $line];
    }

    public function updateCollection($model)
    {

        $valDefinition = $this->getModelVariables($model);
        $blackListKeys = ['assignee_id', 'creator_id'];
        if(isset($valDefinition['verbs'])){
            foreach ($valDefinition['verbs'] as $key => $value) {
                try{
                    if(isset($model->$key) && !in_array($key, $blackListKeys)){
                        $model->$key = explode('|', $value)[$model->$key - 1];

                }
                }catch(\Exception $e){
                    $model->$key = $value;
                }
            }
            return $model;
        }
    }
}