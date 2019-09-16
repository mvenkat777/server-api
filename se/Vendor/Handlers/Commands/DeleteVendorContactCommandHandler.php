<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class DeleteVendorContactCommandHandler implements CommandHandler
{
    /**
     * $vendorRepository 
     * @var object
     */
    private $vendorRepository;

    /**
     * $contatcRepository 
     * @var object
     */
    private $contactRepository;

    /**
     * @param vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository,
                                ContactRepository $contactRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $vendor = $this->vendorRepository->showVendor($command);
        if ($vendor) {
            return $this->contactRepository->deleteContacts([$command->contactId]);
        }
        else{
            throw new SeException('address not related with this vendor', 400, 8640101);
        }
    }
}

