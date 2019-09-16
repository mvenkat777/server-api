<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserDetailRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use App\User;
use App\UserDetail;
use App\UserToken;
use Platform\App\Events\EventGenerator;
use Platform\App\Events\EventDispatcher;
use Rhumsaa\Uuid\Uuid;

class UserDetailsCommandHandler implements CommandHandler
{
    use EventGenerator;

    protected $userDetails;

    protected $userRepo;

    public function __construct(UserRepository $userRepo, UserDetailRepository $userDetails)
    {
        $this->userDetails = $userDetails;
        $this->userRepo = $userRepo;
    }

    public function handle($command)
    {
        $ban= $this->userRepo->isBanned($command->userId);
        if ($ban->isBanned == 0) {
            $msg=$this->userDetails->userDetailsAddOrUpdate([
                        'getColumn' => ['name' => 'user_id', 'value' => $command->userId],
                        'setColumn' => ['user_id'=>$command->userId, 'first_name' => $command->firstName, 'last_name' => $command->lastName,
                                         'country' => $command->country, 'city' =>$command->city,
                                         'state' => $command->state, 'mobile_number' =>$command->mobileNumber,
                                         'location' => json_encode($command->location)]
                    ]);
            if ($msg = 'success') {
                return 'success';
            }
            return ['Update Denied'];
        }
        return ['User Baned'];
    }
}
