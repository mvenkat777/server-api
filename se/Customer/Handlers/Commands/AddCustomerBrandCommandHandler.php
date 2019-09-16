<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class AddCustomerBrandCommandHandler implements CommandHandler
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var CustomerBrandRepository
     */
    private $customerBrandRepository;

    /**
     * @param CustomerRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                CustomerBrandRepository $customerBrandRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->customerBrandRepository = $customerBrandRepository;
    }

    /**
     * @param  CreateCustomerCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $customer = $this->customerRepository->showCustomer($command);
        if($customer){
            $brand = $this->addCustomerBrands($command, $customer);
        }
        else{
            throw new SeException("Customer id doesn't exist", 400, 8670105);
        }
        
        \DB::commit();
        return $customer;
    }

    public function addCustomerBrands($command, $customer)
    {
        foreach ($command->brands as $brand) {
            $this->customerBrandRepository->addBrand($brand, $customer->id);
        }
    }
}

