<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Authentication\Providers\GoogleProvider;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Helpers\Helpers;
use Platform\App\Exceptions\SeException;
use Platform\Authentication\Validators\AuthValidator;

class AuthenticateGoogleUserCommandHandler implements CommandHandler
{
	/**
	 * @var GoogleProvider
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
			GoogleProvider $googleProvider,
	        UserRepository $userRepository,
	        DefaultCommandBus $commandBus,
			UserTokenRepository $userTokenRepository
			)
	{
		$this->googleProvider = $googleProvider;
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->userTokenRepository = $userTokenRepository;
	}

	/**
	 * Handles Platform\Authentication\Commands\AuthenticateGoogleUserCommand
	 * @param  Platform\Authentication\Commands\AuthenticateGoogleUserCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$socialUser = $this->googleProvider->getUserByToken($command->token);

		if($socialUser){
//            if(!Helpers::isSeEmail($socialUser['email'])){
  //              throw new SeException('You are not allowed here', 422, 3210112);
    //        }
			$user = $this->userRepository->getByEmail($socialUser['email']);

			if(!$user){
				$socialUser['isSocial'] = true;
				$socialUser['provider'] = 3;
				$socialUser['isPasswordChangeRequired'] = true;
				$user = $this->commandBus->execute(new CreateUserCommand($socialUser));

				$user->providers()->sync([3], false);
			}

			//if(!AuthValidator::isEligible($user) || !Helpers::isSeEmail($user->email)){
			//	throw new SeException('You are not eligible for login', 422, 3210112);
			//}

			$user->providers()->sync([3], false);

			return $this->userTokenRepository->authenticateSocialUser($user->id);
		}

		throw new SeException('Google Token Not Valid', 422, 3210102);
	}

}
