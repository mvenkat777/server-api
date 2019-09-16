<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Authentication\Providers\FacebookProvider;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Helpers\Helpers;
use Platform\App\Exceptions\SeException;
use Platform\Authentication\Validators\AuthValidator;

class AuthenticateFacebookUserCommandHandler implements CommandHandler
{
	/**
	 * @var FacebookProvider
	 */
	protected $provider;

	/**
	 * @var UserRepository
	 */
    protected $userRepository;

    /**
     * @var DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var UserTokenRepository
     */
	private $userTokenRepository;

	public function __construct(
			FacebookProvider $provider,
	        UserRepository $userRepository,
	        DefaultCommandBus $commandBus,
			UserTokenRepository $userTokenRepository
			)
	{
		$this->provider = $provider;
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->userTokenRepository = $userTokenRepository;
	}

	/**
	 * Handle facebook authentication
	 * @param  AuthenticateFacebookUserComman $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$socialUser = $this->provider->getUserByToken($command->token);

		if($socialUser){
			$user = $this->userRepository->findBy('email', $socialUser['email']);

			if(!$user){
				$socialUser['isSocial'] = true;
				$socialUser['provider'] = 2;
				$socialUser['isPasswordChangeRequired'] = true;
				$user = $this->commandBus->execute(new CreateUserCommand($socialUser));

				$user->providers()->sync([2], false);
			}

			if(!AuthValidator::isEligible($user)){
				throw new SeException('You are not eligible for login', 422, 3210112);
			}

			$user->providers()->sync([2], false);

			return $this->userTokenRepository->authenticateSocialUser($user->id);
		}

		throw new SeException('Facebook Token Not Valid', 422, 3210101);
	}

}
