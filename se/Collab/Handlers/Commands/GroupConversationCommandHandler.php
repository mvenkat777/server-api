<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Platform\Collab\Middleware\CheckConversationMiddleware;
use Platform\Collab\Helpers\FrameDirectConversation;
use Platform\Collab\Repositories\Repository;
use Platform\Users\Transformers\MetaUserTransformer;

/**
* GroupConversationCommandHandler $command
* @return mixed
*/
class GroupConversationCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\DirectMessageRepository
     */
    private $directMessageRepository;

    /**
     * @var Platform\Collab\Repositories\DirectMessagePermissionRepository
     */
    private $messagePermissionRepository;

    /**
     * @var Platform\Collab\Middleware\CheckConversationMiddleware;
     */
    private $middleware;

    /**
     * @var Platform\Collab\Repositories\FrameDirectConversation
     */
    private $frame;

	public function __construct(DirectMessageRepository $directMessageRepository, 
								DirectMessagePermissionRepository $messagePermissionRepository,
								CheckConversationMiddleware $middleware,
								FrameDirectConversation $frame)
	{
		$this->directMessageRepository     = $directMessageRepository;
		$this->middleware 			       = $middleware;
		$this->frame 				       = $frame;
		$this->messagePermissionRepository = $messagePermissionRepository;
	}

	/**
     * @param  GetDirectMessageCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		/**
		 * To Validate, if the inviting user had any past conversation
		 * with the requested user. If not, create a new one
		 */ 
		$conversationId = $this->middleware->validateIfConversationExists($command);
		if($conversationId){
			return $this->directMessageRepository->getConversationHistory($conversationId);
		} else {
			$conversationId = $this->generateNewChatId($command);
			if($conversationId){
				$command->convId = $conversationId;
				$message = $this->generateBotMessgae($command);
				$isAdded = $this->directMessageRepository->store($message);
				$conv = $this->directMessageRepository->getConversationHistory($conversationId);	
                $conv->user = (new MetaUserTransformer)->transform($this->initiator);
                return $conv;
			} else {
				throw new SeException("Failed to start new conversation", 500, '9002500');
				
			}
		}
	}
}