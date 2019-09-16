<?php 
namespace Platform\Dashboard\Transformers;

use App\Task;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Line\Transformers\LineMinimalTransformer;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\Priority\Transformers\PriorityTransformer;
use Platform\Tasks\Helpers\TaskHelper;

class FeedTaskTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Task $task)
    {
        $priorities = $this->item($task->priority, new PriorityTransformer);
        $priorities = $this->manager->createData($priorities)->toArray();

        $line = isset($task->tnaItem) ? (isset($task->tnaItem->tna) ? (isset($task->tnaItem->tna->streamStyle)? (isset($task->tnaItem->tna->streamStyle->line)? $task->tnaItem->tna->streamStyle->line :NULL) : NULL) : NULL) : NULL;
        if ($line) {
            $customer = [
                'customerId' =>  $line->customer->id,
                'code' => $line->customer->code,
                'name' => $line->customer->name,
            ];

            $line = $this->item($line, new LineMinimalTransformer);
            $line = $this->manager->createData($line)->toArray()['data'];
        }


        $creator = [
            'id' => $task->creator->id,
            'email' => $task->creator->email,
            'displayName' => $task->creator->display_name
        ];

        return [
            'id' => (string)$task->id,
            'creator' => $creator,
            'title' => (string)$task->title,
            'dueDate' => $task->due_date,
            'priority' => $priorities['data'],
            'line' => isset($line)? $line : [],
            'customer' => isset($customer)? $customer : [],
            'status' => $task->status->status,
            'type' => TaskHelper::checkType($task),
            'isCompleted' => $task->is_completed
        ];
    }
}