<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\NoteRepository;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class CreateNoteCommandHandler implements CommandHandler
{
    /**
     * @var UserDetailRepository
     */
    private $noteRepo;

    /**
     * @var UserTokenRepository
     */
    private $tokenRepo;

    /**
     * @param UserTokenRepository
     * @param UserRepository
     */
    public function __construct(NoteRepository $noteRepo, UserTokenRepository $tokenRepo)
    {
        $this->noteRepo = $noteRepo;
        $this->tokenRepo = $tokenRepo;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {   
        $user = $this->tokenRepo->getIdByToken($command->token);
        
        return $this->noteRepo->createNote($command, $user->user_id);
	}
    
	
}