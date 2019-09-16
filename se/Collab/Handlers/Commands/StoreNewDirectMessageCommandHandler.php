<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Extractor\UrlExtractor;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Helpers\FrameDirectConversation;

/* To store new direct conversation between two users */
class StoreNewDirectMessageCommandHandler implements CommandHandler {

	/**
     * @var Platform\Collab\Repositories\DirectMessageRepository
     */
    private $directMessageRepository;

    /**
     * @var Platform\Collab\Extractor\UrlExtractor
     */
    private $extractUrl;

    /**
     * @var Platform\Collab\Repositories\FrameDirectConversation
     */
    private $frame;

    public function __construct(DirectMessageRepository $directMessageRepository, 
    							UrlExtractor $extractUrl,
								FrameDirectConversation $frame)
	{
		$this->directMessageRepository = $directMessageRepository;
		$this->extractUrl 			   = $extractUrl;
		$this->frame 				   = $frame;
	}

	/**
     * @param  StoreNewDirectMessageCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$message = $this->generateNewMessage($command);
		if($message){
			$isAdded = $this->directMessageRepository->update($command->convId, $column = 'messages', $message);
			if($isAdded){
                $message['chatId'] = $command->convId;
                $message['data'] = $message['message'];
                unset($message['message']);
                return $message;
				// return $this->directMessageRepository->getConversationHistory($command->convId);	
			}		
		} else {
			throw new SeException("Failed to start new conversation", 500, '9002500');
			
		}
	}

	public function generateNewMessage($command)
	{
		return $this->frame->designForNewDirectMessage($command);
	}
}
