<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;

class VerifyUserCommandHandler implements CommandHandler 
{
	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function handle($command)
	{
		$user = $this->userRepository->getByVerificationCode($command->code);
		if(!$user){
			throw new SeException('Wrong Verification Code', 403, 3210105);
        } else if($user->is_active == true) {
            throw new SeException('Your account is already activated. Please check mail for login details.');
        }
		return $this->userRepository->verifyUser($command, $user);
	}

}
