<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class AddCustomerPartnerCommandHandler implements CommandHandler
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

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
                                CustomerPartnerRepository $customerPartnerRepository,
                                DefaultCommandBus $commandBus)
    {
        $this->customerRepository = $customerRepository;
        $this->customerPartnerRepository = $customerPartnerRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  AddCustomerPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $customer = $this->customerRepository->showCustomer($command);
        if($customer){
            $partner = $this->addCustomerPartners($command, $customer);

        }
        else{
            throw new SeException("Customer Id doesn't exist", 400, 8670105);
        }
        \DB::commit();
        return $customer;
    }

    /**
     * @param AddCustomerPartnerCommand $command 
     * @param string $customer
     */
    public function addCustomerPartners($command, $customer)
    {
        foreach ($command->partners as  $value) {
            $partner = $this->customerPartnerRepository->addPartner($value, $customer->id);
            if(isset($value['contact']) && $value['contact'] != "")
                $this->addCustomerPartnerContact($value, $partner->id);
            if(isset($value['address']) && $value['address'] != "")
                $this->addCustomerPartnerAddress($value, $partner->id);
        }
    }

    public function addCustomerPartnerContact($partner, $partnerId)
    {
        $partnerContact =$this->commandBus->execute(new CreateContactCommand($partner['contact'][0]));
        $this->customerPartnerRepository->addContact($partnerId, $partnerContact->id);
    }

    public function addCustomerPartnerAddress($partner, $partnerId)
    {
        $partnerContact =$this->commandBus->execute(new CreateAddressCommand($partner['address'][0]));
        $this->customerPartnerRepository->addAddress($partnerId, $partnerContact->id);
    }
}

