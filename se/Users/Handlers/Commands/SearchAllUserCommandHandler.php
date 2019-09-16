<?php

namespace Platform\Users\Handlers\Commands;

use Helper;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Helpers\Helpers;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Transformers\UserTagTransformer;
use Platform\Users\Transformers\UserTransformer;

class SearchAllUserCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Users\Repositories\Contracts\UserRepository
	 */
	protected $userRepo;

	/**
	 * @var Platform\Users\Repositories\Contracts\TagRepository
	 */
	protected $tagRepo;

	/**
	 * @var League\Fractal\Manager
	 */
	protected $fractal;

	/**
	 * @param UserRepository $userRepo 
	 * @param TagRepository  $tagRepo  
	 */
	function __construct(UserRepository $userRepo, TagRepository $tagRepo)
	{
		$this->userRepo = $userRepo;
		$this->tagRepo = $tagRepo;
		$this->fractal = new Manager();
	}

	/**
	 * @param  Platform\Users\Commands\SearchAllUserCommand $command 
	 * @return mixed         
	 */
	public function handle($command)
	{
		return $this->userRepo->listAllUser($command);
	}
}