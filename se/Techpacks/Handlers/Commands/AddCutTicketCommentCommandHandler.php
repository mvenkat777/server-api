<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\CutTicketCommentRepository;

class AddCutTicketCommentCommandHandler implements CommandHandler 
{
    protected $commentRepository;

    public function __construct(CutTicketCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param  AddTechpackCommentCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $comment = $this->commentRepository->addComment($command);
        return $comment;
    }
}
