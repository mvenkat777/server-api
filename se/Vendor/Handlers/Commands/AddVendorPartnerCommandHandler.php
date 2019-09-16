<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class AddVendorPartnerCommandHandler implements CommandHandler
{
    /**
     * @var VendorRepository
     */
    private $vendorRepository;

    /**
     * @var VendorPartnerRepository
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
                                VendorPartnerRepository $vendorPartnerRepository,
                                DefaultCommandBus $commandBus)
    {
        $this->vendorRepository = $vendorRepository;
        $this->vendorPartnerRepository = $vendorPartnerRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  AddVendorPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $vendor = $this->vendorRepository->showVendor($command);
        if($vendor){
            $partner = $this->addVendorPartners($command, $vendor);
        }
        else{
            throw new SeException("Vendor Id doesn't exist", 400, 8670105);
        }
        \DB::commit();
        return $vendor;
    }

    /**
     * @param AddVendorPartnerCommand $command 
     * @param string $vendor
     */
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
}

