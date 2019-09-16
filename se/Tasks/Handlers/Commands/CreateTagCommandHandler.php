<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TagRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class CreateTagCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TagRepository
	 */
	protected $tagRepository;

	/**
	 * @var Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;
	
	/**
	 * @param TagRepository $tagRepository
	 * @param Guard         $auth         
	 * @param TaskValidator $taskValidator
	 */
	public function __construct(TagRepository $tagRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->tagRepository = $tagRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  CreateTagCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		return $this->tagRepository->createTag($command);
	}


}
