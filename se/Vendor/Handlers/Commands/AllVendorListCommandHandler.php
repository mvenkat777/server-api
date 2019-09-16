<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class AllVendorListCommandHandler implements CommandHandler
{
    /**
     * @var VendorRepository
     */
    private $vendorRepository;

    /**
     * @param VendorRepository
     */
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
       
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        return $this->vendorRepository->getAllVendor($command);
    }

}

