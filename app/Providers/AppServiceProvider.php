<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;
use Queue;
use Redis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        \DB::listen(function ($sql, $bindings, $time) {
            $pattern = '/".*?"/';
            preg_match_all($pattern, $sql, $matches);
            if (isset($matches[0][0])) {
                $table = preg_replace('/"/', '', $matches[0][0]);
                $executionTime = json_encode([['table' => $table, 'time' => $time]]);
                if (!empty(getenv('ET'))) {
                    $et = json_decode(getenv('ET'));
                    array_push($et, json_decode($executionTime)[0]);
                    $et = json_encode($et);
                    putenv("ET=$et");
                } else {
                    putenv("ET=$executionTime");
                }
            }
        });

        Queue::after(function ($connection, $job, $data) {
            $command = unserialize($data['data']['command']);
            Redis::lpush('completed', json_encode($data));
        });

        Queue::failing(function ($connection, $job, $data) {
            $command = unserialize($data['data']['command']);
            Redis::lpush('failed', json_encode($data));
        });
        
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
