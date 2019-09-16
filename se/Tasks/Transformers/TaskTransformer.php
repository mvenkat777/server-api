<?php
namespace Platform\Tasks\Transformers;

use App\Task;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Priority\Transformers\PriorityTransformer;
use Platform\Tasks\Transformers\AttachmentTransformer;
use Platform\Tasks\Transformers\CategoryTransformer;
use Platform\Tasks\Transformers\CommentTransformer;
use Platform\Tasks\Transformers\FollowerTransformer;
use Platform\Tasks\Transformers\TaskStatusNoteTransformer;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Line\Transformers\StyleTransformer;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\TNA\Transformers\MetaTNATransformer;
use App\Style;
use Platform\Tasks\Helpers\TaskHelper;

class TaskTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Task $task)
    {
        $attachments = $this->collection($task->attachments, new AttachmentTransformer);
        $attachments = $this->manager->createData($attachments)->toArray();

        $comments = $this->collection($task->comments, new CommentTransformer);
        $comments = $this->manager->createData($comments)->toArray();

        $categories = $this->collection($task->categories, new CategoryTransformer);
        $categories = $this->manager->createData($categories)->toArray();

        $tags = $this->collection($task->tags, new TagTransformer);
        $tags = $this->manager->createData($tags)->toArray();

        $followers = $this->collection($task->followers, new FollowerTransformer);
        $followers = $this->manager->createData($followers)->toArray();

        $priority = $this->item($task->priority, new PriorityTransformer);
        $priority = $this->manager->createData($priority)->toArray();

        $creator = $this->item($task->creator, new MetaUserTransformer);
        $creator = $this->manager->createData($creator)->toArray();

        $assignee = $this->item($task->assignee, new MetaUserTransformer);
        $assignee = $this->manager->createData($assignee)->toArray();

        $taskStatusNote = $this->collection($task->statusNote, new TaskStatusNoteTransformer);
        $taskStatusNote = $this->manager->createData($taskStatusNote)->toArray();

        $tna = null;
        $style = null;
        $customer = null;
        $line = null;
        $isMilestone = false;
        $dependentItems = [];

        if(!is_null($task->tnaItem)) {
            $isMilestone = $task->tnaItem->is_milestone;
            $tna = $task->tnaItem->tna;
            $style = Style::where('tna_id', $tna->id)->first();
            if(!is_null($style)) {
                $line = (new MetaLineTransformer)->transform($style->line);
                $style = (new StyleTransformer)->transform($style);
            }
            $customer = (new MetaCustomerTransformer)->transform($tna->customer);
            $tna = (new MetaTNATransformer)->transform($tna);
        }

        if($isMilestone) {
            $items = $task->tnaItem->dependents;
            foreach($items as $item) {
                $dependentItems[] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'plannedDate' => $item->planned_date,
                    'status' => $this->getTNAItemStatus($item),
                    'representator' => (new MetaUserTransformer)->transform($item->representor)
                ];
            }
        }

        return [
            'id' => (string)$task->id,
            'userId' => (string)$task->creator_id,
            'title' => (string)$task->title,
            'description' => (string)$task->description,
            'assignee' => $assignee['data'],
            'dueDate' => $task->due_date,
            'seen' => $task->seen,
            'isSubmitted' => $task->is_submitted,
            'isCompleted' => $task->is_completed,
            'completionDate' => $task->completion_date,
            'priority' => $priority['data'],
            'location' => json_decode($task->location),
            'status' => $task->status->status,
            'deletedAt' => $task->deleted_at,
            'createdAt' => $task->created_at->toDateTimeString(),
            'updatedAt' => $task->updated_at->toDateTimeString(),
            'creator' => $creator['data'],
            'submissionDate' => $task->submission_date,
            'notes' => $taskStatusNote['data'],
            //'assigneeStatus' => $task->assigneeStatuses->status,
            //'snoozedTime' => $task->snoozedTime,
            'attachments' => $attachments['data'], 'comments' => $comments['data'],
            'categories' => $categories['data'],
            'tags' => $tags['data'],
            'followers' => $followers['data'],
            'isMilestone' => $isMilestone,
            'dependentItems' => $dependentItems,
            'tna' => $tna,
            'style' => $style,
            'line' => $line,
            'customer' => $customer,
            'tabStatus' => $this->getTabStatus($task),
            'type' => TaskHelper::checkType($task),
            'archivedAt' => $task->archived_at
        ];

    }
    private function getTabStatus($task)
    {
        if(is_null(\Auth::user())) {
            return "SE Bot";
        }

        if($task->status->status === 'submitted') {
            return 'submitted';
        }

        if($task->status->status === 'completed') {
            return 'archived';
        }

        if(\Auth::user()->id === $task->assignee_id) {
            return 'assigned';
        }

        if(\Auth::user()->id === $task->creator_id) {
            return 'me';
        }

        if(in_array(\Auth::user()->id, array_column($task->followers->toArray(), 'follower_id'))) {
            return 'followed';
        }
    }

	/**
	 * Get TNAItem status depending on the item completed/dispatched
	 * @param  [type] $tnaItem [description]
	 * @return [type]          [description]
	 */
	private function getTNAItemStatus($tnaItem)
	{
		$isCompleted = $this->isItemCompleted($tnaItem);
		$isDispatched = $this->isItemDispatched($tnaItem);

		if($isDispatched && !$isCompleted)
			return 'active';
		elseif ($isCompleted && $isDispatched)
			return 'closed';
		else
			return 'pending';
	}

	private function isItemCompleted($tnaItem)
	{
		return is_null($tnaItem->is_completed) ? false : $tnaItem->is_completed;
	}

	private function isItemDispatched($tnaItem)
	{
		return is_null($tnaItem->is_dispatched) ? false : $tnaItem->is_dispatched;
	}
}
