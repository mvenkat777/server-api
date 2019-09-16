<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\NoteRepository;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class GetNoteCommandHandler implements CommandHandler
{
    /**
     * @var NoteRepository
     */
    private $noteRepo;

    /**
     * @var UserTokenRepository
     */
    private $tokenRepo;

    /**
     * @param UserTokenRepository
     * @param NoteRepository
     */
    public function __construct(NoteRepository $noteRepo, UserTokenRepository $tokenRepo)
    {
        $this->noteRepo = $noteRepo;
        $this->tokenRepo =$tokenRepo;
    }

    /**
     * @param  GetNoteCommand
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->tokenRepo->getIdByToken($command->token);
        return $this->noteRepo->getNote($user, $command);
	}
    
	
}

