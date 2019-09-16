<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Repositories\Contracts\AddressRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use Platform\Vendor\Repositories\Contracts\BankRepository;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class DeleteVendorCommandHandler implements CommandHandler
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
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * @var VendorPartnerRepository
     */
    private $partnerRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(VendorRepository $vendorRepository,
                                ContactRepository $contactRepository,
                                BankRepository $bankRepository,
                                AddressRepository $addressRepository,
                                VendorPartnerRepository $partnerRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->bankRepository = $bankRepository;
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
        $vendor = $this->vendorRepository->showVendor($command);
        if($vendor){
            $addresses = $vendor->addresses()->lists('id');
            $this->addressRepository->deleteAddresses($addresses);

            $contacts = $vendor->contacts()->lists('id');
            $this->contactRepository->deleteContacts($contacts);

            $partners = $vendor->partners()->lists('id');
            $this->partnerRepository->deletePartner($partners);

            $banks = $vendor->banks()->lists('id');
            $this->bankRepository->deleteBanks($banks);

            $result = $this->vendorRepository->deleteVendor($command);
            \DB::commit();
            return $result;
        }
        else{
            throw new SeException("Invalid Id", 400, 8460107);
            
        }
    }
}

