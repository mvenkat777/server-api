<?php

namespace Platform\Users\Handlers\Commands;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Repositories\Contracts\NoteRepository;
use Platform\Users\Repositories\Contracts\TagUserRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Transformers\UserNoteTransformer;
use Platform\Users\Transformers\UserTagTransformer;

class ShowUserByIdCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Users\Repositories\Contracts\UserRepository
	 */
	protected $userRepo;

	/**
	 * @var Platform\Users\Repositories\Contracts\NoteRepository
	 */
	protected $noteRepo;

	/**
	 * @var Platform\Users\Repositories\Contracts\TagRepository
	 */
	protected $tagRepo;

	/**
	 * @var Platform\Authentication\Repositories\Contracts\UserTokenRepository
	 */
	protected $tokenRepo;

	/**
	 * @var League\Fractal\Manager
	 */
	protected $fractal;

	/**
	 * @param UserRepository      $userRepo  
	 * @param NoteRepository      $noteRepo  
	 * @param TagUserRepository   $tagRepo   
	 * @param UserTokenRepository $tokenRepo 
	 */
	function __construct(
		UserRepository $userRepo,
		NoteRepository $noteRepo,
		TagUserRepository $tagRepo,
		UserTokenRepository $tokenRepo
	) {
		$this->userRepo = $userRepo;
		$this->noteRepo = $noteRepo;
		$this->tagRepo = $tagRepo;
		$this->tokenRepo = $tokenRepo;
		$this->fractal = new Manager();
	}

	/**
	 * @param  ShowUserByIdCommand $command 
	 * @return mixed          
	 */
	public function handle($command)
	{
		$user = $this->tokenRepo->getIdByToken($command->token);

		return $this->userRepo->getUserById($command->userId);
	}
}