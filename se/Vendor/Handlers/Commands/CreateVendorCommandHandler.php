<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Vendor\Repositories\Contracts\BankRepository;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class CreateVendorCommandHandler implements CommandHandler
{
    /**
     * @var vendorRepository
     */
    private $vendorRepository;

    /**
     * @var bankRepository
     */
    private $bankRepository;

    /**
     * @var vendorPartnerRepository
     */
    private $vendorPartnerRepository;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param ContactRepository
     */
    public function __construct(VendorRepository $vendorRepository,
        BankRepository $bankRepository,
        VendorPartnerRepository $vendorPartnerRepository,
        DefaultCommandBus $commandBus
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->bankRepository = $bankRepository;
        $this->vendorPartnerRepository = $vendorPartnerRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  CreatevendorCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $vendor = $this->vendorRepository->createVendor($command);

        $partner = $this->addVendorPartners($command, $vendor);
        $brand = $this->addVendorBanks($command, $vendor);
        $address = $this->addVendorAddresses($command, $vendor);
        $contact = $this->addVendorContact($command, $vendor);
        $types = $this->addVendorTypes($command, $vendor);
        $service = $this->addVendorServices($command, $vendor);
        $capabilities = $this->addVendorCapabilities($command, $vendor);
        $paymentTerms = $this->addVendorPaymentTerms($command, $vendor);
        
        \DB::commit();
        return $vendor;
    }

    public function addVendorBanks($command, $vendor)
    {
        foreach ($command->banks as $bank) {
            $bankDetail = $this->bankRepository->addBankDetails($bank);
            $this->vendorRepository->addBank($vendor->id, $bankDetail->id);
            $this->addBankAddress($bank['address'], $bankDetail->id);
        }
    }

    public function addBankAddress($address, $bankId)
    {
        $bankAddress =$this->commandBus->execute(new CreateAddressCommand($address));
        $this->bankRepository->addAddress($bankId, $bankAddress->id);
    }

    public function addVendorPartners($command, $vendor)
    {
        foreach ($command->partners as  $value) {
           $partner = $this->vendorPartnerRepository->addPartner($value, $vendor->id);
           if(isset($value['contact']) && $value['contact'] != "")
               $this->addVendorPartnerContact($value, $partner->id);
           if(isset($value['address']) && $value['address'] != "")
                   $this->addVendorPartnerAddress($value, $partner->id);
        }
    }

    public function addVendorAddresses($command, $vendor)
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
                $this->vendorRepository->addAddress($vendor->id, $address->id);
            }
        }
        elseif($command->addresses != []){
            throw new SeException("More the one primary address is not possible", 400, 8670100);
        }
    }

    public function addVendorContact($command, $vendor)
    {
        foreach ($command->contacts as $contact) {
            $contact =$this->commandBus->execute(new CreateContactCommand($contact));
            $this->vendorRepository->addContact($vendor->id, $contact->id);
        }
    }

    public function addVendorPartnerContact($partner, $partnerId)
    {
        $partnerContact =$this->commandBus->execute(new CreateContactCommand($partner['contact'][0]));
        $this->vendorPartnerRepository->addContact($partnerId, $partnerContact->id);
    }

    public function addVendorPartnerAddress($partner, $partnerId)
    {
        $partnerAddress =$this->commandBus->execute(new CreateAddressCommand($partner['address'][0]));
        $this->vendorPartnerRepository->addAddress($partnerId, $partnerAddress->id);
    }

    public function addVendorTypes($command, $vendor)
    {
        $this->vendorRepository->addTypes($vendor->id, $command->types);
    }

    public function addVendorServices($command, $vendor)
    {
        $this->vendorRepository->addServices($vendor->id, $command->services);
    }

    public function addVendorCapabilities($command, $vendor)
    {
        // $capability = [];
        if (!empty($command->capabilities)) {
            foreach ($command->capabilities as $key => $value) {
                $capability[$value['id']] = ['inhouse'=>$value['inhouse'],
                                                'outsource'=>$value['outsource'],
                                                'moq'=>$value['moq'],
                                                'capacity'=>$value['capacity']];
            }
        } else {
            $capabilities = \App\VendorCapability::get();
            foreach ($capabilities as $capab) {
                $capability[$capab->id] = ['inhouse'=> false,
                                                'outsource'=> false,
                                                'moq'=> NULL,
                                                'capacity'=> NULL];
            }
        }
       
        $this->vendorRepository->addCapabilities($vendor->id, $capability, 'created');

        
        
    }

    public function addVendorPaymentTerms($command, $vendor)
    {
        $this->vendorRepository->addPaymentTerms($vendor->id, $command->paymentTerms);
    }
}

