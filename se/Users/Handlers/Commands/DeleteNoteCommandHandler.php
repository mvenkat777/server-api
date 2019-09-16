<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\NoteRepository;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class DeleteNoteCommandHandler implements CommandHandler
{
    /**
     * @var UserDetailRepository
     */
    private $noteRepo;

    /**
     * @var UserDetailRepository
     */
    private $tokenRepo;

    /**
     * @param NoteRepository
     * @param UserTokenRepository
     */
    public function __construct(NoteRepository $noteRepo, UserTokenRepository $tokenRepo)
    {
        $this->noteRepo = $noteRepo;
        $this->tokenRepo = $tokenRepo;
    }

    /**
     * @param  DeleteNoteCommand
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->tokenRepo->getIdByToken($command->token);
        $data=$this->noteRepo->deleteNote($command, $user->user_id);

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