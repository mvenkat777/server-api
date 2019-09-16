<?php

namespace Platform\Contacts\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Contacts\Repositories\Contracts\ContactRepository;

class AllContactListCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $contactRepository;

    /**
     * @param ContactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
       
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        return $this->contactRepository->getAllContacts($command);
    }

}

