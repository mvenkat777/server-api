<?php

namespace Platform\Contacts\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Contacts\Repositories\Contracts\ContactRepository;

class GetContactByUserIdCommandHandler implements CommandHandler
{
    
    private $contactRepository;

    /**
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
        return $this->contactRepository->showUserContact($command);
    }
}

