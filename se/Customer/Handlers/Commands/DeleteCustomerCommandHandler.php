<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\Address\Repositories\Contracts\AddressRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class DeleteCustomerCommandHandler implements CommandHandler
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * @var AddressRepository
     */
    private $partnerRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                ContactRepository $contactRepository,
                                AddressRepository $addressRepository,
                                CustomerPartnerRepository $partnerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->contactRepository = $contactRepository;
        $this->addressRepository = $addressRepository;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $customer = $this->customerRepository->showCustomer($command);
        if ($customer) {
            $addresses = $customer->addresses()->lists('id');
            $this->addressRepository->deleteAddresses($addresses);
            
            $contacts = $customer->contacts()->lists('id');
            $this->contactRepository->deleteContacts($contacts);

            $partners = $customer->partners()->lists('id');
            $this->partnerRepository->deletePartner($partners);

            $result = $this->customerRepository->deleteCustomer($command);
            \DB::commit();
            return $result;
        }
        else{
            throw new SeException("Error Processing Request", 500, 8640107);
        }
    }
}

