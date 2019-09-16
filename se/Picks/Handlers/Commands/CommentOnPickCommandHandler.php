<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Picks\Repositories\Contracts\PickRepository;
use Platform\App\Exceptions\SeException;
use Platform\Picks\Repositories\Contracts\PickCommentRepository;

class CommentOnPickCommandHandler implements CommandHandler 
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

        return $this->comment->addComment($command->pickId, $command->data);
	}

}
