<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class AllCustomerListCommandHandler implements CommandHandler
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @param ContactRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
       
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        return $this->customerRepository->getAllcustomers($command);
    }

}

