<?php
namespace Platform\Tasks\Transformers;

use App\Task;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Priority\Transformers\PriorityTransformer;
use Platform\Tasks\Transformers\CategoryTransformer;
use Platform\Tasks\Transformers\TagTransformer;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Line\Transformers\StyleTransformer;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\TNA\Transformers\MetaTNATransformer;
use App\Style;
use Platform\Tasks\Helpers\TaskHelper;

class MetaTaskTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Task $task)
    {
        $categories = $this->collection($task->categories, new CategoryTransformer);
        $categories = $this->manager->createData($categories)->toArray();

        $tags = $this->collection($task->tags, new TagTransformer);
        $tags = $this->manager->createData($tags)->toArray();

        $priorities = $this->item($task->priority, new PriorityTransformer);
        $priorities = $this->manager->createData($priorities)->toArray();

        $creator = $this->item($task->creator, new MetaUserTransformer);
        $creator = $this->manager->createData($creator)->toArray();

        $assignee = $this->item($task->assignee, new MetaUserTransformer);
        $assignee = $this->manager->createData($assignee)->toArray();

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

        return [
            'id' => (string)$task->id,
            'userId' => (string)$task->creator_id,
            'creator' => $creator['data'],
            'assignee' => $assignee['data'],
            'title' => (string)$task->title,
            'decription' => (string)$task->description,
            'dueDate' => $task->due_date,
            'seen' => $task->seen,
            'priority' => $priorities['data'],
            'status' => $task->status->status,
            'categories' => $categories['data'],
            'isMilestone' => $isMilestone,
            'tna' => $tna,
            'style' => $style,
            'line' => $line,
            'customer' => $customer,
            'type' => TaskHelper::checkType($task),
            'isCompleted' => $task->is_completed,
            'archivedAt' => $task->archived_at
        ];
    }

}
