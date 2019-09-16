<?php

namespace Platform\Address\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Address\Commands\CreateUserAddressCommand;
use Platform\Address\Repositories\Contracts\AddressRepository;

class UpdateAddressCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $addressRepository;

    /**
     * @param AddressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->addressRepository->updateAddress($command);
    }
}

