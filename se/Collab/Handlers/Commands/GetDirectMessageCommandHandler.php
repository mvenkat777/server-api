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

/* To store the direct conversastion between two user */
class GetDirectMessageCommandHandler extends Repository implements CommandHandler{

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
		if(count($command->participant) > 1){
			$conversationId = $this->middleware->validateIfGroupConversationExists($command);
			if($conversationId){
				$conv = $this->directMessageRepository->getGroupConversationHistory($conversationId['chatId']);
				$favourites = $this->messagePermissionRepository->getGroupConversation($command->initiator, $conversationId['chatId']);
				$chatData = $favourites['group'][$conversationId['chatId']];
				if($conv->messages){
					$foundKey = array_intersect(array_unique($chatData['favourites']), array_column($conv->messages, 'id'));
					$allMessages = $this->makeFavourite($foundKey, $conv);
					$conv['messages'] = $allMessages;
				}
				$user = [];
				foreach ($command->participant as $participant) {
					$user[] = $this->userFramedData('id', $participant);
				}
				$conv->user = $user;
				return $conv;
			} else {
				$user = [];
				foreach ($command->participant as $participant) {
					$user[] = $this->userFramedData('id', $participant);
				}
				$command->groupId = $this->generateNewGroupId($command);
				$message = $this->generateGroupBotMessage($command);
				$isAdded = $this->directMessageRepository->store($message);
				$conv = $this->directMessageRepository->getGroupConversationHistory($command->groupId);	
				$conv->user = $user;
                return $conv;
			}
		} else {
			$command->participant = $command->participant[0];
			$conversationId = $this->middleware->validateIfConversationExists($command);
			if($conversationId){
				$conv = $this->directMessageRepository->getConversationHistory($conversationId);
				$favourites = $this->messagePermissionRepository->getConversationIdByUserId($command->initiator, $command->participant);
				if($conv->messages){
					$foundKey = array_intersect(array_unique($favourites->chat[$command->participant]['favourites']), array_column($conv->messages, 'id'));
					$allMessages = $this->makeFavourite($foundKey, $conv);
					$conv['messages'] = $allMessages;
				}
				$conv->user = (new MetaUserTransformer)->transform(\App\User::where('id', $command->participant)->first());
				return $conv;
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

	protected function makeFavourite($favouriteId, $messages)
	{
		// dd(array_search($messages['messages'][0]['id'], $favouriteId));
		$collection = [];
		foreach ($messages['messages'] as $key => $value) {
			if(array_search($value['id'], $favouriteId) !== false){
				$collection[$key] = $messages['messages'][$key];
				$collection[$key]['isFavourite'] =true;
			} else {
				$collection[$key] = $messages['messages'][$key];
			}
		}
		return $collection;
	}

	public function generateNewChatId($command)
	{
		$forInitiator = $this->frame->designForNewConv($command);
		$uniqueChatId = $forInitiator['chat'][$command->participant]['chatId'];
		$registerInitiator = $this->messagePermissionRepository->registerNewConversation($forInitiator);
		if($registerInitiator){
			$registerParticipant = $this->messagePermissionRepository->registerNewConversation($this->overwriteCommand($command, $uniqueChatId));
			return $registerInitiator->chat[$command->initiator]['chatId'];
		} else {
			throw new SeException("Failed to create new conversation", 500, 'SE_9002500');	
		}
	}

	public function generateNewGroupId($command)
	{
		$dummyArray = $this->frame->designForNewGroupConv($command);
		foreach ($dummyArray['group'] as $key => $value) {
			$data = [];
			foreach ($value['participant'] as $count => $id) {
				$data['userId'] = $id;
				$data['group'] = $dummyArray['group'];
				$registerInitiator = $this->messagePermissionRepository->registerNewGroupConversation($data);
			}
			return $value['chatId'];
		}
	}

	/**
	 * To overwrite the collab command
	 * TO synchonise the same chatId for both user [initiator, and participant]
	 * @var object command
	 */
	public function overwriteCommand($command, $uniqueChatId)
	{
		$command->initiator = $command->participant;
		$command->participant = \Auth::user()->id;
		$framed = $this->frame->designForNewConv($command);
		$framed['chat'][$command->participant]['chatId'] = $uniqueChatId;
		return $framed;
	}

	public function generateNewMessage($command)
	{
		return $this->frame->designForNewDirectMessage($command);
	}

	public function generateBotMessgae($value)
	{
		/**
		 * Here, the participant and initiator actual Id's is comming after getting swapped with each other
		 * As because, it is getting overwritten in the overwriteCommand() function.
		 *
		 */
        $this->participant = \App\User::where('id', $value->participant)->first();
		$value->participantDisplayName = $this->participant->display_name;
        $this->initiator = \App\User::where('id', $value->initiator)->first();
		$value->initiatorDisplayName = $this->initiator->display_name;
		return [
			'chatId' => $value->convId,
			'messages' => [
				[
					'id' => $this->generateUUID(),
					'message' => 'This is a very begining of your direct message between with "'.$value->participantDisplayName.' and '.$value->initiatorDisplayName.'". Direct messages are private between two of you.',
					'type' => 'SE-BOT',
					'isFavourite' => false
				],
			],
			'archived' => [
			]
		];
	}

	public function generateGroupBotMessage($value)
	{
		/**
		 * Here, the participant and initiator actual Id's is comming after getting swapped with each other
		 * As because, it is getting overwritten in the overwriteCommand() function.
		 *
		 */
        return [
			'chatId' => $value->groupId,
			'messages' => [
				[
					'id' => $this->generateUUID(),
					'message' => 'This is a very begining of your group chat',
					'type' => 'SE-BOT',
					'isFavourite' => false
				],
			],
			'archived' => [
			]
		];
	}
}
