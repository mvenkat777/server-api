<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;

class UpdatePasswordCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Users\Repositories\Contracts\UserRepository
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

		return $this->userRepository->updatePassword($command, \Auth::user());
	}

}