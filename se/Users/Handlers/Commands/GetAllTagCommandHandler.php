<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\TagUserRepository;

class GetAllTagCommandHandler implements CommandHandler
{
    /**
     * @var TagRepository
     */
    private $tagRepo;

    /**
     * @param TagRepository
     */
    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }

    /**
     * @param  AddTagCommand
     * @return mixed
     */
    public function handle($command)
    {
       return $this->tagRepo->getAll($command); 
	}
}