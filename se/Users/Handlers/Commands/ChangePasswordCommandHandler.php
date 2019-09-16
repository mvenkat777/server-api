<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Validators\UserValidator;
use Platform\App\Exceptions\SeException;

class ChangePasswordCommandHandler implements CommandHandler 
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
		if(!UserValidator::isSamePassword($command->currentPassword, \Auth::user())){
			throw new SeException('Password Mismatch', 422, 3210007);
		}

		return $this->userRepository->changePassword($command, \Auth::user());
	}

}