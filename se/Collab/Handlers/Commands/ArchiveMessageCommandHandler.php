<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\Repository;

/* To store the direct conversastion between two user */
class ArchiveMessageCommandHandler extends Repository implements CommandHandler{

	/**
     * @var Platform\Collab\Repositories\MessageRepository
     */
    private $messageRepository;

    public function __construct(DirectMessageRepository $messageRepository)
	{
		$this->messageRepository     = $messageRepository;
	}

	/**
     * @param  ArchiveCardCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		/**
		 * To archive a message of a requsested chat
		 * 
		 */ 
		$status = $this->messageRepository->archive($command->chatId, $command->messageId);
		if($status){
			return $status;
		} else {
			throw new SeException("Something is wrong. Please check your input", 422, '9006422');
		}
	}
}