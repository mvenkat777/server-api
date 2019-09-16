<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Platform\Collab\Repositories\Repository;

/**
* To fetch a message details from DB
* @return mixed
*/
class GetMessageByIdCommandHandler extends Repository implements CommandHandler
{
	/**
     * @var Platform\Collab\Repositories\DirectMessageRepository
     */
    private $directMessageRepository;

    /**
     * @var Platform\Collab\Repositories\DirectMessagePermissionRepository
     */
    private $permissionRepository;
	
    function __construct(DirectMessageRepository $directMessageRepository, DirectMessagePermissionRepository $permissionRepository)
	{
		$this->directMessageRepository = $directMessageRepository;
        $this->permissionRepository = $permissionRepository;
	}

	/**
     * @param  StoreNewCollabCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
        $message = $this->directMessageRepository->getMessageById($command->chatId, $command->messageId);
        $participant = $this->regexParticipant($command->chatId, \Auth::user()->id);
        
        $favouriteMessages = [];
        if(!empty($participant)) {
            $favouriteMessages = $this->permissionRepository->getConversationIdByUserId(\Auth::user()->id, $participant);
            $favouriteMessages = array_unique($favouriteMessages->chat[$participant]['favourites']);
        } else {
            $favouriteMessages = $this->permissionRepository->getConversationIdByUserIdOfGroup(\Auth::user()->id, $command->chatId);
            $favouriteMessages = array_unique($favouriteMessages['favourites']);
        }

        $foundKey = array_search($command->messageId, $favouriteMessages);
        if($foundKey !== false) {
            $data['chatId'] = $command->chatId;
            $data['messages'][0] = $this->calculateFavourites(
                $this->getExactMessage($message->messages, $command->messageId), 
                $favouriteMessages,
                $command->chatId
            );
            return json_decode(json_encode($data), false);
        }
        $message['chatId'] = $command->chatId;
        return $message;
	}

    private function calculateFavourites($message, $favouriteMessages, $chatId)
    {
        $message['isFavourite'] = in_array($message['id'], $favouriteMessages);
        $message['chatId'] = $chatId;
        return $message;
    }

    private function getExactMessage($message, $messageId)
    {
    	foreach ($message as $key => $value) {
    		if($value['id'] == $messageId)
    			return $value;
    	}
    	return [];
    }
}