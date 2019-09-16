<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Commanding\DefaultCommandBus;

class SendActivityNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Activitation Notification';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notification = new \Platform\Observers\SendNotification\SendNotificationAll();
        $data = $notification->getAllData();
        $view = 'emails.ActivityNotification.Notification';
        $subject = 'Notifications From Sourceeasy';
        $count = 1;
        echo 'In Progress...' . PHP_EOL;
        foreach ($data as $key => $value) {
            \Mail::send($view, ['data' => $value], function ($message) use ($key, $subject){
                $message->to($key)->subject($subject);
            });
            if($this->getCompletedPercentage($count, count($data)) != 100){
                echo $this->getCompletedPercentage($count, count($data)).'% done' . PHP_EOL;
            }
            else{
                echo 'Thankyou, Notification Mail Sent Successfully' . PHP_EOL;
            }
            $count++;
        }
    }

    /**
     * @param 
     * @return count
     */
    public function getCompletedPercentage($presentCount, $totalCount){
        $percent = ($presentCount / $totalCount) * 100;
        $count = number_format($percent, 0);
        return $count;
    }
}
