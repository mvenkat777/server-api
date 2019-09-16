<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class CreateCustomerCommandHandler implements CommandHandler
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
     * @var CustomerPartnerRepository
     */
    private $customerPartnerRepository;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param ContactRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                CustomerBrandRepository $customerBrandRepository,
                                CustomerPartnerRepository $customerPartnerRepository,                           DefaultCommandBus $commandBus)
    {
        $this->customerRepository = $customerRepository;
        $this->customerBrandRepository = $customerBrandRepository;
        $this->customerPartnerRepository = $customerPartnerRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  CreateCustomerCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $customer = $this->customerRepository->createCustomer($command);

        $partner = $this->addCustomerPartners($command, $customer);
        $brand = $this->addCustomerBrands($command, $customer);
        $address = $this->addCustomerAddresses($command, $customer);
        $contact = $this->addCustomerContact($command, $customer);
        $contact = $this->addCustomerTypes($command, $customer);
        $contact = $this->addCustomerServices($command, $customer);
        $contact = $this->addCustomerRequirements($command, $customer);
        $contact = $this->addCustomerPaymentTerms($command, $customer);
        
        \DB::commit();
        return $customer;
    }

    public function addCustomerBrands($command, $customer)
    {
        foreach ($command->brands as $brand) {
            $this->customerBrandRepository->addBrand($brand, $customer->id);
        }
    }

    public function addCustomerPartners($command, $customer)
    {
        foreach ($command->partners as  $value) {
            $partner = $this->customerPartnerRepository->addPartner($value, $customer->id);
            if(isset($value['contact']) && $value['contact'] != ""){
                $this->addCustomerPartnerContact($value, $partner->id);
            }
            if(isset($value['address']) && $value['address'] != ""){
                $this->addCustomerPartnerAddress($value, $partner->id);
            }
        }
    }

    public function addCustomerAddresses($command, $customer)
    {
        $count = 0;
        foreach ($command->addresses as $address) {
            if($address['isPrimary'] == true){
                $count ++;
            }
        }
        if($count == 1){
            foreach ($command->addresses as $value) {
                $address = $this->commandBus->execute(new CreateAddressCommand($value));
                $this->customerRepository->addAddress($customer->id, $address->id, 'add');
            }
        }
        elseif($command->addresses != []){
            throw new SeException("More then one primary address is not possible", 400, 8670100);
            
        }
    }

    public function addCustomerContact($command, $customer)
    {
        $contact =$this->commandBus->execute(new CreateContactCommand($command->contacts));
        $this->customerRepository->addContact($customer->id, $contact->id, 'add');
    }

    public function addCustomerPartnerContact($partner, $partnerId)
    {
        $partnerAddress =$this->commandBus->execute(new CreateContactCommand($partner['contact'][0]));
        $this->customerPartnerRepository->addContact($partnerId, $partnerAddress->id);
    }

    public function addCustomerPartnerAddress($partner, $partnerId)
    {
        $partnerContact =$this->commandBus->execute(new CreateAddressCommand($partner['address'][0]));
        $this->customerPartnerRepository->addAddress($partnerId, $partnerContact->id);
    }

    public function addCustomerTypes($command, $customer)
    {
        $this->customerRepository->addTypes($customer->id, $command->types, 'add');
    }

    public function addCustomerServices($command, $customer)
    {
        $this->customerRepository->addServices($customer->id, $command->services, 'add');
    }

    public function addCustomerRequirements($command, $customer)
    {
        $this->customerRepository->addRequirements($customer->id, $command->requirements, 'add');
    }

    public function addCustomerPaymentTerms($command, $customer)
    {
        $this->customerRepository->addPaymentTerms($customer->id, $command->paymentTerms, 'add');
    }
}

