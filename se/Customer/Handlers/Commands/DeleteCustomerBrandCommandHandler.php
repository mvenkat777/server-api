<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class DeleteCustomerBrandCommandHandler implements CommandHandler
{
    /**
     * $customerRepository 
     * @var object
     */
    private $customerRepository;

    /**
     * $brandRepository 
     * @var object
     */
    private $brandRepository;

    /**
     * @param CustomerRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                CustomerBrandRepository $brandRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @param  DeleteCustomerBrandCommand
     * @return mixed
     */
    public function handle($command)
    {
        $customer = $this->customerRepository->showCustomer($command);
        if ($customer) {
            return $this->brandRepository->delete($command->brandId);
        }
        else{
            throw new SeException('brand not related with this customer', 400, 8460104);
        }
    }
}

