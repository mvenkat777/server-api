<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\HttpLogs\Logs\GetDailyLogFiles;
use Carbon\Carbon;
use Platform\HttpLogs\Repositories\Eloquent\EloquentLogRepository;
use Platform\App\Commanding\DefaultCommandBus;

class InsertLogData extends Command
{
    public $filePath;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log Insert Command';

/**
     * @var Platform\Commands\DefaultCommandBus
     */
    
    protected $commandBus;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Starting Process...");
        $this->commandBus->execute(new \Platform\HttpLogs\Commands\CreateLogCommand(storage_path()."/logs/LoggerInfo/logs/api-http-".Carbon::now()->format('Y-m-d').".log", '/var/log/syslog'));
        $this->info("Its Done...");
    }
}
