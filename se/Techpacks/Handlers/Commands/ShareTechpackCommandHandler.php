<?php

namespace Platform\Techpacks\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\Techpacks\Mailer\TechpackMailer;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackUserRepository;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Mailer\UserMailer;
use Platform\Users\Repositories\Contracts\UserRepository;

class ShareTechpackCommandHandler implements CommandHandler
{
    /**
     * @var Platform\Techpacks\Repositories\Contracts\TechpackUserRepository
     */
    protected $techpackUserRepository;

    /**
     * @var Platform\Users\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var Platform\Techpacks\Mailer\TechpackMailer
     */
    protected $techpackMailer;

    /**
     * @var Platform\Techpacks\Repositories\Contracts\TechpackRepository
     */
    protected $techpackRepository;

    /**
     * @param TechpackUserRepository $techpackUserRepository
     * @param UserRepository         $userRepository
     * @param DefaultCommandBus      $commandBus
     * @param TechpackMailer         $techpackMailer
     * @param TechpackRepository     $techpackRepository
     */
    public function __construct(
        TechpackUserRepository $techpackUserRepository,
        UserRepository $userRepository,
        DefaultCommandBus $commandBus,
        TechpackMailer $techpackMailer,
        TechpackRepository $techpackRepository
    ) {
        $this->techpackUserRepository = $techpackUserRepository;
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->techpackMailer = $techpackMailer;
        $this->techpackRepository = $techpackRepository;
    }

    /**
     * Handles ShareTechpackCommand
     * @param  ShareTechpackCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $users = $command->users;
        $techpackId = $command->techpackId;

        $techpack = $this->techpackRepository->find($techpackId);

        if (!$techpack) {
        	throw new SeException('Techpack with this id could not be found', 404, 6010104);
        }

        foreach ($users as $user) {
            $email = $user['email'];
            if (!Helpers::isValidEmail($email)) {
                continue;
            }

            $registeredUser = $this->userRepository->getByEmail($email);

            if ($registeredUser) {
            	if ($this->techpackRepository->isOwner($techpackId, $registeredUser->id)) {
            	    continue;
            	}
                $shared = $techpack->users()
                				   ->sync([$registeredUser->id => ['permission' => 'can_read']]);
                if ($shared) {
                    $this->techpackMailer->techpackShared($registeredUser, [
                        'techpackUrl' => "http://techpack.io/app/#/share/$techpackId",
                        'displayName' => isset(Auth::user()->displayName) ? Auth::user()->displayName : Auth::user()->email,
                    ]);
                }

                continue;
            }
            $user['password'] = Helpers::makeTemporaryPassword($email);
            $registeredUser = $this->commandBus->execute(new CreateUserCommand($user, true));

			$shared = $techpack->users()
							   ->sync([$registeredUser->id => ['permission' => 'can_read']], false);

            if ($shared) {
                $this->techpackMailer->techpackSharedWithNewUser(
                	$registeredUser,
                	[
                        'password' => $user['password'],
                        'displayName' => isset(Auth::user()->displayName) ? Auth::user()->displayName : Auth::user()->email,
                    ]
                );
            }
        }
        return $this->techpackRepository->find($techpackId);
    }
}
