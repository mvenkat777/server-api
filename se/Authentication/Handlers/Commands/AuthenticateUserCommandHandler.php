<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Commands\AuthenticateUserCommand;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\Users\Validators\UserValidator;
use Platform\Authentication\Validators\AuthValidator;
use Platform\Tasks\Helpers\TaskHelper;

class AuthenticateUserCommandHandler implements CommandHandler
{
	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @var UserTokenRepository
	 */
	protected $userTokenRepository;

	/**
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
	 * @param UserRepository      $userRepository
	 * @param UserTokenRepository $userTokenRepository
	 * @param TaskHelper $taskHelper
	 */
	public function __construct(
		UserRepository $userRepository,
		UserTokenRepository $userTokenRepository,
		TaskHelper $taskHelper
	) {
		$this->userRepository = $userRepository;
		$this->userTokenRepository = $userTokenRepository;
		$this->taskHelper = $taskHelper;
	}

	/**
	 * @param  AuthenticateUserCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$user = $this->userRepository->getByEmail($command->email);

		if(UserValidator::isSocialUser($user) && !UserValidator::isLegacyUser($user)){
			throw new SeException('Please sign in using google/facebook', 405, 3210104);
		}

		AuthValidator::isEligible($user);

		if (!UserValidator::isUserActive($user)) {
		    throw new SeException(
		        'Please Activate your account',
		        403,
		        3210106
		    );
		}

		$authenticatedUser = $this->userTokenRepository->authenticateUser($command);

		if(AuthValidator::isSendTasksRequired($authenticatedUser->user)){
			$this->taskHelper->sendAssignedTask($authenticatedUser->user);
		}

		return $authenticatedUser;
	}


}
