<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Repositories\Contracts\AddressRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class DeleteVendorAddressCommandHandler implements CommandHandler
{
    /**
     * $vendorRepository 
     * @var object
     */
    private $vendorRepository;

    /**
     * $addressRepository 
     * @var object
     */
    private $addressRepository;

    /**
     * @param vendorRepository
     * @param AddressRepository
     */
    public function __construct(VendorRepository $vendorRepository,
                                AddressRepository $addressRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param  DeletevendorPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {
        $vendor = $this->vendorRepository->showVendor($command);
        if ($vendor) {
            return $this->addressRepository->deleteAddresses([$command->addressId]);
        }
        else{
            throw new SeException('address not related with this vendor', 400, 8640102);
        }
    }
}

