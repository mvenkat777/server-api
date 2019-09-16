<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Validators\CreateCollabValidator;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\Customer\Commands\AddSalesRepresentativeToCollabCommand;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;

class ActivateCollabCommandHandler implements CommandHandler 
{
    /**
     * @var CreateCollabValidator
     */
    private $validator;

    /**
     * @var CollabRepository 
     */
    private $collab;

    /**
     * @var DefaultCommandBus 
     */
    private $commandBus;

    /**
     * @param CreateCollabValidator $validator
     * @param CollabRepository $collab
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(
        CreateCollabValidator $validator,
        CollabRepository $collab,
        DefaultCommandBus $commandBus
    ) {
        $this->validator = $validator;
        $this->collab = $collab;
        $this->commandBus = $commandBus;
	}

    /**
     * Activates the collab for a customer
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $collabExists = $this->collab->getByCustomerIdWithRelations($command->customerId);
        if($collabExists) {
            throw new SeException("Collab for this customer is already activated.", 409);
        }
        $this->validator->validate($command->data);
        $collab = $this->collab->addCollab($command->data, $command->customerId);
        if ($collab) {
            return $this->collab->getByCustomerIdWithRelations($command->customerId);
        }
        return false;
	}
}
