<?php

namespace Platform\Contacts\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use Platform\Users\Repositories\Contracts\UserRepository;



class CreateContactCommandHandler implements CommandHandler
{
 
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @param ContactRepository
     */
    public function __construct(ContactRepository $contactRepository, UserRepository $userRepo)
    {
        
        $this->contactRepository = $contactRepository;
        $this->userRepo = $userRepo;
       
    }

    /**
     * @param  CreateContactCommand
     * @return mixed
     */
    public function handle($command)
    {   
        return $this->contactRepository->makeContact($command);
    }
}

