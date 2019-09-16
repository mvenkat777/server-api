<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\DirectMessage\Validators\MessageValidator;
use Vinkla\Pusher\PusherManager;

use Platform\DirectMessage\Commands\GetDirectMessageCommand;
use Platform\DirectMessage\Commands\StoreDirectMessageCommand;
use Platform\DirectMessage\Commands\UpdateDirectMessageCommand;
use Platform\DirectMessage\Commands\GetSharedDataCommand;
use Platform\DirectMessage\Commands\GetDirectMessageChatIdCommand;
use Platform\DirectMessage\Commands\UpdateToSeenCommand;

/**
* DirectMessageController
*/
class DirectMessageController extends ApiController
{
	/**
     * For calling validator
     * @var Platform\DirectMessage\Validators\MessageValidator
     */
	public $validator;

	/**
	 * For sending data from pusher
     * @var Vinkla\Pusher\PusherManager
     */
    public $pusher;

    /**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

	public function __construct(DefaultCommandBus $commandBus, 
								MessageValidator $validator,
								PusherManager $pusher)
	{
		$this->validator = $validator;
		$this->commandBus = $commandBus;
		$this->pusher = $pusher;

		parent::__construct(new Manager());
	}

	/**
     * Get direct messages by a user
     * @param string $userId
     * @param string $id
     * @return mixed
     */
	public function index($id = NULL)
	{
		$data = [ 'userId' => \Auth::user()->id, 'id' => $id];
		$result = $this->commandBus->execute(new GetDirectMessageCommand($data));
		if($id == NULL){
			return $this->respondWithArray(['data' => array_reverse($this->sortByTime($result))]);
		}
		return $this->respondWithArray(['data' => $result]);
	}

	/**
     * To generate new chatId if not exists or fetching the existing one
     * @param array $members
     * @return chatId
     */
	public function generateChatId(Request $request)
	{
		if(count($request->all()['members']) == 1){
			if($request->all()['members'][0] == \Auth::user()->id){
				throw new SeException("Invalid Request", 422, '9001422');
				
			}
		}
		$result = $this->commandBus->execute(new GetDirectMessageChatIdCommand($request->all()));
		if(count($result['members']) > 2){
			$result['isGroup'] = true;
		} else{
			$result['isGroup'] = false;
		}
		return $this->respondWithArray(['data' => $result]);
	}

	/**
     * Store new direct message
     * @return mixed
     */
	public function store(Request $request, $chatId)
	{
		$isValidated = $this->validator->validateInput($request->all());
		if(!$isValidated)
			throw new SeException("Valid Input Required", 422, '4001422');

		$result = $this->commandBus->execute(new StoreDirectMessageCommand($request->all(), $chatId));
		$data = [ 'userId' => \Auth::user()->id, 'id' => NULL];
		$getChatObject = $this->commandBus->execute(new GetDirectMessageCommand($data));
		
		$this->pusher->trigger(
                    'direct-'.$result['chatId'], 
                    'Direct Messages', 
                    ['data' => $result['message']]
                );
		$position = array_search($chatId, array_column($getChatObject, 'chatId'));
		$user = array_column($getChatObject[$position]['members'], 'id');
		foreach($user as $userId)
		{
			$this->pusher->trigger(
                'direct-list-'.$userId, 
                'Direct Messages', 
                ['data' => $getChatObject[$position]]
            );
		}
		return $this->respondWithArray(['data' => $result['message']]);
	}

	/**
     * Update a direct messages by message id
     * @param Request $request
     * @param string id
     * @return mixed
     */
	public function update(Request $request, $chatId, $messageId)
	{
		$data = ['data' => $request->all(),'chatId' => $chatId, 'messageId' => $messageId];
		$result = $this->commandBus->execute(new UpdateDirectMessageCommand($data));
		return $this->respondWithArray(['data' => $result]);
	}

	/**
     * Get all shared files and image by user in a direct message or group
     * @param string id
     * @return mixed
     */
	public function getShared($id)
	{
		return ;
		$data = [ 'userId' => \Auth::user()->id, 'chatId' => $id];
		$result = $this->commandBus->execute(new GetSharedDataCommand($data));
		$this->pusher->trigger(
                    'direct-'.$userId, 
                    'Direct Messages', 
                    ['data' => $result]
                );
	}

	/**
	 * To sort an array on the basis of ascending order
	 * @param array collection
	 * @return mixed
	 */
	public function sortByTime($collection)
	{
		usort($collection, function($a, $b) { //Sort the array using a user defined function
            return $a['lastMessage']['createdAt'] > $b['lastMessage']['createdAt'] ? 1 : -1; //Compare the scores
        });                                                                                                                                                                                                        
        return $collection;
	}

    /**
     * To update the message as seen message
     * @param string chatId
     * @return mixed
     */
    public function seen($chatId, $messageId)
    {
        $data = [ 'userId' => \Auth::user()->id, 'chatId' => $chatId, 'messageId' => $messageId];
        $result = $this->commandBus->execute(new UpdateToSeenCommand($data));
        return $this->respondWithArray(['data' => $result]);
    }
}