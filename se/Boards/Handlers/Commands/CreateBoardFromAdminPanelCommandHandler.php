<?php

namespace Platform\Boards\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Boards\Commands\CreateBoardCommand;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class CreateBoardFromAdminPanelCommandHandler implements CommandHandler
{
	/**
	 * @var DefaultCommandBus
	 */
	private $commandBus;

	/**
	 * @var CustomerRepository
	 */
	private $customer;

	/**
	 * @param DefaultCommandBus
	 * @param CustomerRepository
	 */
	public function __construct(DefaultCommandBus $commandBus, CustomerRepository $customer)
	{
		$this->commandBus = $commandBus;
		$this->customer = $customer;
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function handle($command)
	{
		$customer = $this->customer->find($command->data['customerId']);
		if (!$customer) {
			throw new SeException("Customer not found.", 404);
		}

		$collab = $customer->collab;
		if (!$collab) {
			throw new SeException("No collab found for the customer.", 404);
		}

		return $this->commandBus->execute(new CreateBoardCommand($collab->url, $command->data));

	}

}
