<?php

namespace Platform\Contacts\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Contacts\Repositories\Contracts\ContactRepository;

class DeleteContactCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $contactRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $get = $this->contactRepository->deleteContact($command);
        if($get)
        {
            return ['Deleted Successfully'];
        }
        else
        {
            return ['Invalid Contact Id'];
        }
    }
}

