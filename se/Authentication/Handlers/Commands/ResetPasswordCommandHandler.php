<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;
use Platform\Authentication\Validators\AuthValidator;

class ResetPasswordCommandHandler implements CommandHandler
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
		$user = $this->userRepository->getByEmail($command->email);
        if(!$user){
            throw new SeException('Email Not Found', 422, 3210109);
        }

        if(!AuthValidator::isResetPinSame($command, $user)){
        	throw new SeException('Reset Pin Invalid', 422, 3210107);
        }

		return $this->userRepository->resetPassword($command, $user);
	}

}