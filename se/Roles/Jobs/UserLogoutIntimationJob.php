<?php

namespace Platform\Roles\Jobs;

use App\Http\Controllers\AuthController;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Platform\App\Jobs\Job;
use Vinkla\Pusher\PusherManager;
use Carbon\Carbon;

class UserLogoutIntimationJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    protected $auth;
    
    /**
     * Create a new job instance.
     *
     * @param  Request  $request
     *         Email $email
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        $this->onQueue('mediumJob');

    }

    /**
     * Execute the job.
     *
     * @param  DefaultCommandBus  $commandBus
     * @return void
     */
    public function handle(PusherManager $pusher, AuthController $auth)
    {
       foreach ($this->user as $key => $value) {
            $pusher->trigger(
                            'logout-'.$value, 
                            'User Logout Intimation', 
                            ['data' => $auth->fetchUserRolesPermissions($value)]
                        );
        }
    }
}