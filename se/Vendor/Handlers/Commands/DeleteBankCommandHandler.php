<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Repositories\Contracts\BankRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class DeleteBankCommandHandler implements CommandHandler
{
    /**
     * $vendorRepository 
     * @var object
     */
    private $vendorRepository;

    /**
     * $bankRepository 
     * @var object
     */
    private $bankRepository;

    /**
     * @param vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository,
                                BankRepository $bankRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->bankRepository = $bankRepository;
    }

    /**
     * @param  DeletevendorbankCommand
     * @return mixed
     */
    public function handle($command)
    {
        $vendor = $this->vendorRepository->showVendor($command);
        if ($vendor) {
            return $this->bankRepository->delete($command->bankId);
        }
        else{
            throw new SeException('bank not related with this vendor', 400, 8640104);
        }
    }
}

