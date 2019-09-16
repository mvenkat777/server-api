<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\TagUserRepository;

class GetUserTagCommandHandler implements CommandHandler
{

    /**
     * @var TagUserRepository
     */
    private $tagUserRepo;

    /**
     * @param TagUserRepository
     */
    public function __construct(TagUserRepository $tagUserRepo)
    {
        $this->tagUserRepo = $tagUserRepo;
    }

    /**
     * @param  AddTagCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->tagUserRepo->getAlTagOfUser($command);
	}
}