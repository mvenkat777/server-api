<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

class BanUserCommandHandler implements CommandHandler
{
	protected $userRepo;
	private $userToken;

	function __construct(UserRepository $userRepo, UserTokenRepository $userToken)
	{
		$this->userRepo = $userRepo;
		$this->userToken = $userToken;
	}

	public function handle($command)
	{
		foreach ($command->id as $key => $userId) 
		{
			
			$this->userToken->deleteByUserId($userId);
			$data[$key] = $this->userRepo->userBanned($userId);
		}
		return 'success';
	}
}