<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Repositories\Contracts\BankRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class AddBankCommandHandler implements CommandHandler
{
    /**
     * @var VendorRepository
     */
    private $vendorRepository;

    /**
     * @var BankRepository
     */
    private $bankRepository;

    /**
     * @param VendorRepository
     */
    public function __construct(VendorRepository $vendorRepository,
        DefaultCommandBus $commandBus,
        BankRepository $bankRepository
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->bankRepository = $bankRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param  CreateCustomerCommand
     * @return mixed
     */
    public function handle($command)
    {   
        \DB::beginTransaction();
        $vendor = $this->vendorRepository->showVendor($command);
        if($vendor){
            $bank = $this->addVendorBanks($command, $vendor);
        }
        else{
            throw new SeException("vendor id doesn't exist", 400, 8670105);
        }
        
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
}

