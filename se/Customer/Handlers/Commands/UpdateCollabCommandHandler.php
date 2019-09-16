<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Validators\UpdateCollabValidator;

class UpdateCollabCommandHandler implements CommandHandler 
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
     * @param UpdateCollabValidator $validator
     * @param CollabRepository $collab
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(
        UpdateCollabValidator $validator,
        CollabRepository $collab
    ) {
        $this->validator = $validator;
        $this->collab = $collab;
	}

    /**
     * Activates the collab for a customer
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $collabExists = $this->collab->getByCustomerIdWithRelations($command->customerId);
        if(!$collabExists) {
            throw new SeException("Collab not found for this cusstomer.", 404);
        }
        $this->validator->validate($command->data);
        $collab = $this->collab->updateCollab($command->data, $command->customerId);
        if ($collab) {
            return $this->collab->getByCustomerIdWithRelations($command->customerId);
        }
        return false;
	}
}

