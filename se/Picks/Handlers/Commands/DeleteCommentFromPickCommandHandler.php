<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Picks\Repositories\Contracts\PickRepository;
use Platform\Picks\Repositories\Contracts\PickCommentRepository;
use Platform\App\Exceptions\SeException;

class DeleteCommentFromPickCommandHandler implements CommandHandler 
{
    /**
     * @var PickRepository
     */
    private $pick;

    /**
     * @var PickCommnetRepository
     */
    private $comment;

    /**
     * @param PickRepository $pick
     * @param PickCommentRepository $comment
     */
	public function __construct(PickRepository $pick, PickCommentRepository $comment)
	{
        $this->pick = $pick;
        $this->comment = $comment;
	}

	public function handle($command)
	{
        $pick = $this->pick->find($command->pickId);
        if (!$pick) {
            throw new SeException("Pick not found.", 404);
        }
        $comment = $this->comment->find($command->commentId);
        if (!$comment) {
            throw new SeException("Comment not found.", 404);
        }

        return $this->comment->delete($command->commentId);
	}
}
