<?php

namespace Platform\Help\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Help\Repositories\Contracts\HelpRepository;

class DeleteHelpBySlugCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $helpRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(HelpRepository $helpRepository)
    {
        $this->helpRepository = $helpRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $get = $this->helpRepository->destroy($command);
        if($get)
        {
            return ['Deleted Successfully'];
        }
        else
        {
            return ['Invalid Slug'];
        }
    }
}

