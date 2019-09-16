<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Helpers\TaskDispatcher;

class PublishTnaItemCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepository;

    /**
     * @var Platform\TNA\Helpers\TaskDispatcher
     */
    protected $taskDispatcher;

	/**
     * @param TaskDispatcher      $taskDispatcher
	 * @param TNAItemRepository   $tnaItemRepo         
	 */
    public function __construct(TaskDispatcher $taskDispatcher,
                                TNAItemRepository $tnaItemRepository)
	{
        $this->taskDispatcher = $taskDispatcher;
		$this->tnaItemRepository = $tnaItemRepository;
	}

	public function handle($command)
	{
        return $this->taskDispatcher->dispatch($command->tnaItem);
	}

}
