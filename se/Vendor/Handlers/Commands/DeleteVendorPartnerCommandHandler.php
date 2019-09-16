<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class DeleteVendorPartnerCommandHandler implements CommandHandler
{
    /**
     * $vendorRepository 
     * @var object
     */
    private $vendorRepository;

    /**
     * $partnerRepository 
     * @var object
     */
    private $partnerRepository;

    /**
     * @param vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository,
                                VendorPartnerRepository $partnerRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * @param  DeletevendorPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {
        $vendor = $this->vendorRepository->showVendor($command);
        if ($vendor) {
            return $this->partnerRepository->delete($command->partnerId);
        }
        else{
            throw new SeException('partner not related with this vendor', 400, 8640103);
        }
    }
}

