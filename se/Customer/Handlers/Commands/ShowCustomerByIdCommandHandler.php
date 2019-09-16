<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class ShowCustomerByIdCommandHandler implements CommandHandler
{
    
    private $customerRepository;

    /**
     * @param AddressRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->customerRepository->showCustomer($command);
    }
}

