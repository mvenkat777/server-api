<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Repositories\Contracts\TNARepository;

class DeleteTNACommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepository;

	/**
	 * @param TNARepository     $tnaRepository     
	 * @param TNAItemRepository $tnaItemRepository 
	 */
	public function __construct(TNARepository $tnaRepository,
								TNAItemRepository $tnaItemRepository)
	{
		$this->tnaRepository = $tnaRepository;
		$this->tnaItemRepository = $tnaItemRepository;
	}

	/**
	 * @param  DeleteTNACommand $command 
	 * @return integer          [number of deleted rows]
	 */
	public function handle($command)
	{
		\DB::beginTransaction();
		$this->tnaItemRepository->deleteItemByTNA($command->tnaId);
		$result = $this->tnaRepository->deleteTNA($command->tnaId);
		\DB::commit();
		return $result;
	}

}