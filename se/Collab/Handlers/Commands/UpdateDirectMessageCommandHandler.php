<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Helpers\DirectMessageHelpers;

/* To store the direct conversastion between two user */
class UpdateDirectMessageCommandHandler extends Repository implements CommandHandler{

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
		if($message['type'] != 'SE-BOT' && $message['owner']['id'] == \Auth::user()->id){
			$message['message'] = $command->message;
			$message['isEdited'] = true;
			$isUpdated = $this->directMessageRepository->updateMessage($command->chatId, $command->messageId,$message);
			if($isUpdated){
				return $this->directMessageRepository->getConversationHistory($command->chatId);	
			} else {
				throw new SeException("Failed to update message. Try again", 422, '9006422');
			}
		} else {
			throw new SeException("You don't have permission", 401, '9002401');
			
		}
	}
}