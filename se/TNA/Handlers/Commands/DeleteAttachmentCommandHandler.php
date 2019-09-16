<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;

class DeleteAttachmentCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @param TNARepository $tnaRepository 
	 */
	public function __construct(TNARepository $tnaRepository)
	{
		$this->tnaRepository = $tnaRepository;
	}

	/**
	 * @param  DeleteAttachmentCommand $command 
	 * @return integer [number of updated rows]      
	 */
	public function handle($command)
	{
		return $this->tnaRepository->deleteAttachment($command->tnaId);
	}

}