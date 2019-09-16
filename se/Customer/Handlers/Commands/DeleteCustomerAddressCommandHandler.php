<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\Address\Repositories\Contracts\AddressRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class DeleteCustomerAddressCommandHandler implements CommandHandler
{
    /**
     * $customerRepository 
     * @var object
     */
    private $customerRepository;

    /**
     * $addressRepository 
     * @var object
     */
    private $addressRepository;

    /**
     * @param CustomerRepository
     * @param AddressRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                AddressRepository $addressRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param  DeleteCustomerPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {
        $customer = $this->customerRepository->showCustomer($command);
        if ($customer) {
            return $this->addressRepository->deleteAddresses([$command->addressId]);
        }
        else{
            throw new SeException('address not related with this customer', 400, 8460102);
        }
    }
}

