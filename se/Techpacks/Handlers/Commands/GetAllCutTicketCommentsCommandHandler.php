<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\CutTicketCommentRepository;

class GetAllCutTicketCommentsCommandHandler implements CommandHandler 
{
    /**
     * @var CutTicketCommentRepository
     * @access private
     */
    private $cutTicket;

    public function __construct(CutTicketCommentRepository $cutTicket)
    {
        $this->cutTicket = $cutTicket;
    }

    public function handle($command)
    {
        return $this->cutTicket->getAllComments($command->techpackId);
    }
}
