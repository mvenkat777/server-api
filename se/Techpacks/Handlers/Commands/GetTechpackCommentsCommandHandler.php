<?php 

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\TechpackCommentRepository;

class GetTechpackCommentsCommandHandler implements CommandHandler
{
	protected $techpackCommentRepository;

	public function __construct(TechpackCommentRepository $techpackCommentRepository)
	{
		$this->techpackCommentRepository = $techpackCommentRepository;
	}

	public function handle($command)
	{
		$techpackId = $command->techpackId;

		return $this->techpackCommentRepository->getByTechpackId($techpackId);
	}

}
