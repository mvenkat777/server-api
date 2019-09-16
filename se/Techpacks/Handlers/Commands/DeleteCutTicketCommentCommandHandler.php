<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\CutTicketCommentRepository;

class DeleteCutTicketCommentCommandHandler implements CommandHandler 
{
    private $cutTicket;

    public function __construct(CutTicketCommentRepository $cutTicket)
    {
        $this->cutTicket = $cutTicket;
    }

    public function handle($command)
    {
        return $this->cutTicket->deleteComment($command->commentId);
    }

}
