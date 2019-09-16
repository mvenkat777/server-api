<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;

class UnBanUserCommandHandler implements CommandHandler
{
	protected $userRepo;

	function __construct(UserRepository $userRepo)
	{
		$this->userRepo = $userRepo;
	}

	public function handle($command)
	{
		foreach ($command->id as $key => $userId) 
		{
			$data[$key] = $this->userRepo->userUnBanned($userId);
		}
		return 'success';
	}
}