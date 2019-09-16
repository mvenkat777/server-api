<?php

namespace Platform\Apps\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Repositories\Contracts\UserTokenRepository;
use Platform\Apps\Repositories\Contracts\AppRepository;


class CreateAppCommandHandler implements CommandHandler
{
	protected $appRepo;
	protected $userRepo;
	protected $tokenRepo;

	function __construct(AppRepository $appRepo, 
						UserRepository $userRepo , 
						UserTokenRepository $tokenRepo)
	{
		$this->appRepo = $appRepo;
		$this->userRepo = $userRepo;
		$this->tokenRepo = $tokenRepo;

	}

	public function handle($command)
	{
		$userId = $this->tokenRepo->getByToken($command->token);
		$userEmail = $this->userRepo->userById($userId->userId);
		
		$command=(array)$command;
		unset($command['token']);//dd($command);
		$app=$this->appRepo->createApp($command);

		return $app;
	}
} 