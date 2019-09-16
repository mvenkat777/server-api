<?php

namespace Platform\Address\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Address\Commands\CreateUserAddressCommand;
use Platform\Address\Repositories\Contracts\AddressRepository;

class DestroyUserAddressCommandHandler implements CommandHandler
{
    use EventGenerator;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var UserRepository
     */
    private $addressRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(EventDispatcher $dispatcher, AddressRepository $addressRepository)
    {
        $this->dispatcher = $dispatcher;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->addressRepository->destroyAddress($command);
    }
}

