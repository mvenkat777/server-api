<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\Authentication\Events\UserForgotPassword;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Validators\UserValidator;

class SendResetPasswordLinkCommandHandler implements CommandHandler
{
	use EventGenerator;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(EventDispatcher $dispatcher, UserRepository $userRepository)
	{
		$this->dispatcher = $dispatcher;
		$this->userRepository = $userRepository;
	}

	public function handle($command)
	{
		if(is_null($command->email)){
			throw new SeException('Email Cannot be empty', 422, 3210109);
		}

		$user = $this->userRepository->getByEmail($command->email);
		if(!$user){
            throw new SeException('Email Not Found', 422, 3210109);
        }

        if($user->isBanned){
            throw new SeException('We have some problems. Please contact admin.', 401, 3210111);
        }

        if (!UserValidator::isLegacyUser($user)) {
            throw new SeException('Login using facebook/google and set your password.', 422, 3210114);
        }

		$token = $this->userRepository->setResetPin($user);
		if(!$token){
			throw new SeException('Some Problem Occured', 500, 50001);
		}

		try{
			$this->sendEmail($command, $token);
			return true;
		}
		catch(\Exception $e){
			return false;
		}
	}

	private function sendEmail($command, $token)
	{
		$this->raise(new UserForgotPassword($command, $token));
        $this->dispatcher->dispatch($this->releaseEvents());
	}

}
