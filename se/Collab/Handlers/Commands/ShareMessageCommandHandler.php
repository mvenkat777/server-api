<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\App\Exceptions\SeException;

/**
* To share a message with a user command handler
* @return mixed
*/
class ShareMessageCommandHandler extends Repository implements CommandHandler
{
	/**
     * @var Platform\Collab\Repositories\DirectMessageRepository
     */
    private $directMessageRepository;
	
	function __construct(DirectMessageRepository $directMessageRepository)
	{
		$this->directMessageRepository = $directMessageRepository;
	}

	/**
     * @param  ShareMessageCommandHandler $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$collection = $this->directMessageRepository->getMessageById($command->chatId, $command->messageId);
		$message = $collection->messages[0]; 
		$message['id'] = $this->generateUUID();
		$isShared = $this->directMessageRepository->update($command->convIdOfSharedUser, $column = 'messages', $message);
		if($isShared){
			return $this->directMessageRepository->getConversationHistory($command->convIdOfSharedUser);	
		} else {
			throw new SeException("Failed to share message. Try again", 422, '9006422');
			
		}
	}
}
