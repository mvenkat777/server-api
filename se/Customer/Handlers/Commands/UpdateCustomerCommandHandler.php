<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\Address\Commands\UpdateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\UpdateContactCommand;
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class UpdateCustomerCommandHandler implements CommandHandler
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
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        \DB::beginTransaction();
        $customer = $this->customerRepository->updateCustomer($command);
        if ($customer) {

            $partners = $this->updateCustomerPartners($command);
            $brand = $this->updateCustomerBrands($command);
            $address = $this->updateCustomerAddresses($command);
            $contact = $this->updateCustomerContact($command);
            $types = $this->updateCustomerTypes($command);
            $services = $this->updateCustomerServices($command);
            $requierments = $this->updateCustomerRequierments($command);
            $PaymentTerms = $this->updateCustomerPaymentTerms($command);

            \DB::commit();
            return $customer;
        }
        else
            throw new SeException("incorrect id", 400, 8460106);

    }

    public function updateCustomerBrands($command)
    {
        $this->customerBrandRepository->deleteAll($command->customerId);
        foreach ($command->brands as $brand) {
            $result = $this->customerBrandRepository->updateBrand($brand, $command->customerId);
        }
    }

    public function updateCustomerPartners($command)
    {
        foreach ($command->partners as  $value) {
           $partner = $this->customerPartnerRepository->updatePartner($value, $command->customerId);
            if(isset($value['contact']) && !empty($value['contact'])) {
                $this->updateCustomerPartnerContact($value);
            }
            if(isset($value['address']) && !empty($value['address'])) {
                $this->updateCustomerPartnerAddress($value);
            }
        }
    }

    public function updateCustomerAddresses($command)
    {
        $count = 0;
        foreach ($command->addresses as $address) {
            $address['isPrimary'] = isset($address['isPrimary']) ? $address['isPrimary'] : false;
            if($address['isPrimary'] == true){
                $count ++;
            }
        }
        if($count == 1){
            foreach ($command->addresses as $value) {
                $address = $this->commandBus->execute(new UpdateAddressCommand($value));
            }
        }
        elseif($command->addresses != []){
            throw new SeException("More then one primary address is not possible", 400, 8670100);

        }
    }

    public function updateCustomerContact($command)
    {
        if ($command->contacts != []) {
            $contact = $this->commandBus->execute(new UpdateContactCommand($command->contacts[0]));
        }
    }

    public function updateCustomerPartnerContact($partner)
    {
        $partnerContact =$this->commandBus->execute(new UpdateContactCommand($partner['contact'][0]));
    }

    public function updateCustomerPartnerAddress($partner)
    {
        $partnerAddress =$this->commandBus->execute(new UpdateAddressCommand($partner['address'][0]));
    }

    public function updateCustomerTypes($command)
    {
        $this->customerRepository->addTypes($command->customerId, $command->types);
    }

    public function updateCustomerServices($command)
    {
        $this->customerRepository->addServices($command->customerId, $command->services);
    }

    public function updateCustomerRequierments($command)
    {
        $this->customerRepository->addRequirements($command->customerId, $command->requirements);
    }

    public function updateCustomerPaymentTerms($command)
    {
        $this->customerRepository->addPaymentTerms($command->customerId, $command->paymentTerms);
    }
}

