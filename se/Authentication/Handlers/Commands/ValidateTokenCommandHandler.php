<?php 

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class ValidateTokenCommandHandler implements CommandHandler
{
	/**
	 * @var UserTokenRepository
	 */
	protected $tokenRepository;

	/**
	 * @param UserTokenRepository $tokenRepository
	 */
	public function __construct(UserTokenRepository $tokenRepository)
	{
		$this->tokenRepository = $tokenRepository;
	}

	public function handle($command)
	{
		return $this->tokenRepository->validateAuthToken($command);
	}


}
