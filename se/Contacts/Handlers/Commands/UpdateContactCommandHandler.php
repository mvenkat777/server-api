<?php

namespace Platform\Contacts\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Contacts\Repositories\Contracts\ContactRepository;

class UpdateContactCommandHandler implements CommandHandler
{
   
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
        $data = $this->contactRepository->updateContact($command);
        
        if($data)
        {
            return ['Updated Successfully'];
        }
        else
        {
            return ['Invalid Contact Id'];
        }
        
    }

}

