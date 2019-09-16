<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class ShowVendorByIdCommandHandler implements CommandHandler
{
    
    private $vendorRepository;

    /**
     * @param VendorRepository
     */
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->vendorRepository->showVendor($command);
    }
}

