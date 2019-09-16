<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Commands\UpdateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\UpdateContactCommand;
use Platform\Vendor\Repositories\Contracts\BankRepository;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class UpdateVendorCommandHandler implements CommandHandler
{
   
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var bankRepository
     */
    private $bankRepository;

    /**
     * @var CustomerPartnerRepository
     */
    private $customerPartnerRepository;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param VendorRepository
     * @param VendorPartnerRepository
     * @param BankRepository
     * @param DefaultCommandBus
     */
    public function __construct(VendorRepository $vendorRepository,
                                BankRepository $bankRepository,
                                VendorPartnerRepository $vendorPartnerRepository,                           DefaultCommandBus $commandBus)
    {
        $this->vendorRepository = $vendorRepository;
        $this->bankRepository = $bankRepository;
        $this->vendorPartnerRepository = $vendorPartnerRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        \DB::beginTransaction();
        $vendor = $this->vendorRepository->updateVendor($command);
        if($vendor){
            $partners = $this->updateVendorPartners($command);
            $brand = $this->updateBanks($command);
            $address = $this->updateVendorAddresses($command);
            $contact = $this->updateVendorContact($command);
            $types = $this->updateVendorTypes($command);
            $services = $this->updateVendorServices($command);
            $capabilities = $this->updateVendorCapabilities($command);
            $PaymentTerms = $this->updateVendorPaymentTerms($command);
            
            \DB::commit();
            return $vendor;
        }
        else
            throw new SeException("Vendor id doesnot exist", 400, 8640106);
            
    }

    public function updateBanks($command)
    {
        foreach ($command->banks as $bank) {
            $result = $this->bankRepository->updateBank($bank);
            $this->updateBankAddress($bank['address']);
        }
    }

    public function updateBankAddress($address)
    {
        $address = $this->commandBus->execute(new UpdateAddressCommand($address));
    }

    public function updateVendorPartners($command)
    {
        foreach ($command->partners as  $value) {
            $partner = $this->vendorPartnerRepository->updatePartner($value, $command->vendorId);
            if(isset($value['contact']) && $value['contact'] != "")
                $this->updateVendorPartnerContact($value);
            if(isset($value['address']) && $value['address'] != "")
                $this->updateVendorPartnerAddress($value);
        }
    }

    public function updateVendorAddresses($command)
    {
        $count = 0;
        foreach ($command->addresses as $address) {
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
            throw new SeException("More the one primary address is not possible", 400, 8670100);
            
        }
    }

    public function updateVendorContact($command)
    {
        foreach ($command->contacts as $contact) {
            $contact = $this->commandBus->execute(new UpdateContactCommand($contact));
        }
    }

    public function updateVendorPartnerContact($partner)
    {
        $partnerContact = $this->commandBus->execute(new UpdateContactCommand($partner['contact'][0]));
    }

    public function updateVendorPartnerAddress($partner)
    {
        $partnerAddress =$this->commandBus->execute(new UpdateAddressCommand($partner['address'][0]));
    }

    public function updateVendorTypes($command)
    {
        $this->vendorRepository->addTypes($command->vendorId, $command->types);
    }

    public function updateVendorServices($command)
    {
        $this->vendorRepository->addServices($command->vendorId, $command->services);
    }

    public function updateVendorCapabilities($command)
    {
        foreach ($command->capabilities as $key => $value) {
            $capability[$value['id']] = ['inhouse'=>$value['inhouse'],
                                            'outsource'=>$value['outsource'],
                                            'moq'=>$value['moq'],
                                            'capacity'=>$value['capacity']];
        }
        $this->vendorRepository->addCapabilities($command->vendorId, $capability);
    }

    public function updateVendorPaymentTerms($command)
    {
        $this->vendorRepository->addPaymentTerms($command->vendorId, $command->paymentTerms);
    }

}

