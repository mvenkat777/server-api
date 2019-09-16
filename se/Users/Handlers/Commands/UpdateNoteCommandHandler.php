<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\NoteRepository;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class UpdateNoteCommandHandler implements CommandHandler
{
    /**
     * @var NoteRepository
     */
    private $noteRepo;

    /**
     * @var TokenUserRepository
     */
    private $tokenRepo;

    /**
     * @param TokenUserRepository
     * @param NoteRepository
     */
    public function __construct(NoteRepository $notrRepo, UserTokenRepository $tokenRepo)
    {
        $this->notrRepo = $notrRepo;
        $this->tokenRepo = $tokenRepo;
    }

    /**
     * @param  UpdateNoteCommand
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->tokenRepo->getIdByToken($command->token);
        $data = $this->notrRepo->updateNote($command , $user->user_id);
        if($data = 1)
        {
            return 'success';
        }
        else
        {
            return 'Fail';
        }
	}
    
	
}