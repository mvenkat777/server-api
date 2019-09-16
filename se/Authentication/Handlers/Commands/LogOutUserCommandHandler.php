<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class LogOutUserCommandHandler implements CommandHandler 
{
	/**
	 * @var UserTokenRepository
	 */
	protected $userTokenRepository;

	/**
	 * @param UserTokenRepository $userTokenRepository
	 */
	public function __construct(UserTokenRepository $userTokenRepository)
	{
		$this->userTokenRepository = $userTokenRepository;
	}

	public function handle($command)
	{
		return $this->userTokenRepository->logOutUser($command);
	}
}