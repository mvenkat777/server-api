<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class DeleteCustomerContactCommandHandler implements CommandHandler
{
    /**
     * $customerRepository 
     * @var object
     */
    private $customerRepository;

    /**
     * $contatcRepository 
     * @var object
     */
    private $contactRepository;

    /**
     * @param CustomerRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                ContactRepository $contactRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $customer = $this->customerRepository->showCustomer($command);
        if ($customer) {
            return $this->contactRepository->deleteContacts([$command->contactId]);
        }
        else{
            throw new SeException('address not related with this customer', 400, 8460101);
        }
    }
}

