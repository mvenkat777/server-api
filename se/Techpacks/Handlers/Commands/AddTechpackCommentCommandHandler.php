<?php 

namespace Platform\Techpacks\Handlers\Commands;

use Vinkla\Pusher\PusherManager;
use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Transformers\TechpackCommentTransformer;
use Platform\Techpacks\Repositories\Contracts\TechpackCommentRepository;

class AddTechpackCommentCommandHandler implements CommandHandler
{
	protected $commentRepository;
	protected $pusher;

	public function __construct(TechpackCommentRepository $commentRepository, PusherManager $pusher)
	{
		$this->commentRepository = $commentRepository;
		$this->pusher = $pusher;
	}

	/**
	 * @param  AddTechpackCommentCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		// check if user has access to the techpack 
		$comment = $this->commentRepository->addComment($command);
		$this->pusher->trigger(
			'techpack-' . $comment->techpackId, 
			'new_comment', 
			(new TechpackCommentTransformer)->transform($comment)
		);
		return $comment;
	}


}
