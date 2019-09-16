<?php

namespace Platform\Slack;

use App\User;
use App\Priority;
use App\TaskStatus;
use Carbon\Carbon;
use App\Task;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class TasksSlack {

	public function generateMessage($target, $data){
		if(isset($data->creator_id))
			$creator = $this->getCreatorDetails($data->creator_id);
		if(isset($data->priority_id))
			$priority = $this->getPriorityValue($data->priority_id)->priority;
		if(isset($data->status_id))
			$status = $this->getStatusValue($data->status_id)->status;
		if(isset($data->due_date))
			$date = Carbon::parse($data->due_date)->format('m-d-Y');
		if(!isset($data->id))
			$details = $this->getTaskDetails(json_decode($data[0])->id);
		$subVariable = str_replace('{{', '', $target->slackMessage);
		$finalVariable = str_replace('}}', ' .', $subVariable);
		// strstr()
		// dd($creator->display_name);
		dd($finalVariable);
		dd($target->slackMessage);
	}

	public function whentaskwascreated($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": has created and assigned a new task for you. Details: ".$data->taskLink;
	}

	public function whensendmailfortaskowner($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return "@".$creator->slack_id.": has sent you task details.
			Task Title : ".$data->title."
			Description : ".$data->description." 
			Due : ".Carbon::parse($data->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($data->priority_id)->priority."
			Status : ".$this->getStatusValue($data->status_id)->status;
	}

	public function whensendmailwithattachementsandcomments($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": has sent you the task details. Check your SE mail";
	}

	public function whensendmailwithattachements($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": has sent you the task details. Check you SE mail";
	}

	public function whentaskwassubmitted($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		$assignee = $this->getCreatorDetails($data->assignee_id);
		return $creator->display_name.": has been requested for approval. Details:
			Task Title : ".$data->title."
			Description : ".$data->description." 
			Due : ".Carbon::parse($data->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($data->priority_id)->priority."
			Status : ".$this->getStatusValue($data->status_id)->status;
	}

	public function whentaskwascompleted($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": has set status to complete. Details:
			Task Title : ".$data->title."
			Description : ".$data->description." 
			Due : ".Carbon::parse($data->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($data->priority_id)->priority."
			Status : ".$this->getStatusValue($data->status_id)->status;
	}

	public function whentaskwasclosed($data){
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": rejected the task. Details:
			Task Title : ".$data->title."
			Description : ".$data->description." 
			Due : ".Carbon::parse($data->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($data->priority_id)->priority."
			Status : ".$this->getStatusValue($data->status_id)->status;
	}

	public function whentaskwasstarted($data)
	{
		$assignee = $this->getCreatorDetails($data->assignee_id);
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": started the task. 
			Task Title : ".$data->title."
			Description : ".$data->description." 
			Due : ".Carbon::parse($data->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($data->priority_id)->priority."
			Status : ".$this->getStatusValue($data->status_id)->status;
	}

	public function whentaskwasreassigned($data)
	{
		$creator = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": reassigned a task. Details: ".$data->taskLink	;
	}

	public function whencommentwasaddedtotask($data)
	{
		$commentOwner = $this->getCreatorDetails($data->creator_id);
		return $creator->display_name.": commented on your task.
			Task Title : ".$data->taskTitle."
			Comment : ".$data->data;
	}

	public function whenfollowerwasaddedtotask($data)
	{
		$details = $this->getTaskDetails($data->task_id);
		$taskOwner = $this->getCreatorDetails($details->creator_id);
		return $creator->display_name.": has added you as a follower in the task.
			Task Title : ".$details->title."
			Description : ".$details->description." 
			Due : ".Carbon::parse($details->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($details->priority_id)->priority."
			Status : ".$this->getStatusValue($details->status_id)->status;
	}

	public function whenduedateisupdatedtotask($data)
	{
		$details = $this->getTaskDetails($data->id);
		$taskOwner = $this->getCreatorDetails($details->creator_id);
		return $creator->display_name.": has updated the due date.
			Task Title : ".$details->title."
			Description : ".$details->description." 
			Due : ".Carbon::parse($details->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($details->priority_id)->priority."
			Status : ".$this->getStatusValue($details->status_id)->status;
	}

	public function whensendoverduedateremindertoassignee($data){
		$details = $this->getTaskDetails(json_decode($data[0])->id);
		$taskOwner = $this->getCreatorDetails($details->creator_id);
		return $creator->display_name.": has marked your task as pending.
			Task Title : ".$details->title."
			Description : ".$details->description." 
			Due : ".Carbon::parse($details->due_date)->format('m-d-Y')."
			Priority : ".$this->getPriorityValue($details->priority_id)->priority."
			Status : ".$this->getStatusValue($details->status_id)->status;
	}

	public function slackMessage(){
		$creator = $this->getCreatorDetails($data->creator_id);
		$priority = $this->getPriorityValue($data->priority_id)->priority;
		$status = $this->getStatusValue($data->status_id)->status;
		$date = Carbon::parse($data->due_date)->format('m-d-Y');
		$details = $this->getTaskDetails(json_decode($data[0])->id);
	}

	public function getCreatorDetails($creator_id){

		return User::where('id','=',$creator_id)->first();
	}

	public function getPriorityValue($priority_id){
		return Priority::where('id','=',$priority_id)->select('priority')->first();
	}

	public function getStatusValue($status_id){
		return TaskStatus::where('id','=',$status_id)->select('status')->first();
	}

	public function getTaskDetails($task_id){
		return Task::where('id',$task_id)->first();
	}
}