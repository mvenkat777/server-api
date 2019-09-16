<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\Tasks\Handlers\Reminder\TaskReminderCommandHandler;

class TaskReminderCommand extends Command
{

    protected $taskReminder;

    /**
     * @param TaskReminderCommandHandler        $taskReminder  
     */
    public function __construct(TaskReminderCommandHandler $taskReminder)
    {
        $this->taskReminder = $taskReminder;
        
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:taskReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check For Pending Tasks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->taskReminder->handle();
    }
}
