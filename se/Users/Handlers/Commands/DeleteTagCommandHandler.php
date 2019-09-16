<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\TagUserRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

class DeleteTagCommandHandler implements CommandHandler
{

    /**
     * @var TagUserRepository
     */
    private $tagUserRepo;

    /**
     * @param TagUserRepository
     */
    public function __construct(
        TagUserRepository $tagUserRepo,
        UserRepository $userRepo
    ) {
        $this->tagUserRepo = $tagUserRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @param  AddTagCommand
     * @return mixed
     */
    public function handle($command)
    {
        $result =$this->tagUserRepo->delete($command);
        
        if($result != 0)
        {
            return 'success';
        }

        return 'User does not having this Tag';
	}
}