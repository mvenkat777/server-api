<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNARepository;

class AddAttachmentCommandHandler implements CommandHandler 
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
	 * @param  AddAttachmentCommand $command 
	 * @return TNA          
	 */
	public function handle($command)
	{
		return $this->tnaRepository->addAttachment((array)$command);
	}

}