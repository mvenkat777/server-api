<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\InsertLogData::class,
        \Platform\App\Console\CreateCommand::class,
        \Platform\App\Console\CreateFeature::class,
        \Platform\App\Console\CreateRepo::class,
        \Platform\App\Console\CreateValidator::class,
        \Platform\App\Console\CreateTransformer::class,
        \Platform\App\Console\TNAPublishCommand::class,
        \Platform\App\Console\TNAHealthCalculation::class,
        \App\Console\Commands\TaskReminderCommand::class,
        \App\Console\Commands\DailyDigestCommand::class,
        \Platform\App\Console\TNACreatePreset::class,
        \App\Console\Commands\ProductStreamGeneration::class,
        \Platform\App\Console\ReportLog::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('log:insert')
        //          ->dailyAt('23:57');

        // $schedule->command('send:notification')
        //          ->dailyAt('23:30');

        // $schedule->command('run:immediate')
        //          ->everyMinute();

        // $schedule->command('run:hourly')
        //          ->everyMinute('5');

        // $schedule->command('run:daily')
        //          ->dailyAt('23.56');

        // $schedule->command('send:reminder')
        //          ->dailyAt('00.00');

        // $schedule->command('tnaAction:published')
        //          ->everyMinute();
    }
}
