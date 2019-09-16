<?php
namespace Platform\DirectMessage\Repositories;
use Platform\DirectMessage\Models\Messages;

/**
* For all the CRUD request related to Message
*/
class MessageRepository
{
	/**
	 * @var $message
	 */
	protected $message;

	/**
	 * @param Platform\DirectMessage\Models\Message
	 */
	public function __construct(Messages $message)
	{
		$this->message = $message;
	}

	/**
	 * For fetching chat history by a chatID between users
	 * @param string $chatId
	 * @return mixed
	 */
	public function getChatHistoryByChatId($chatId)
	{
		return $this->message->where('chatId', $chatId)->first();
	}

	public function storeMessage($data)
	{
		return $this->message->where('chatId', $data['chatId'])->push(['messages' => $data['message']]);
	}

	public function createMessageFrame($data)
	{
		$this->message->create($data);
	}

	public function updateMessage($data, $userId)
	{
		$isUpdated = $this->message->where('chatId', $data['chatId'])
								->where('messages.messageId', $data['messageId'])
								->where('messages.owner.id', $userId)
								->update([
							 	'messages.$.message' => $data['update']
							 ]);
		if($isUpdated)
		{
			return $this->message->where('chatId', $data['chatId'])
								->where('messages.messageId', $data['messageId'])
								->where('messages.owner.id', $userId)
								->update([
							 	'messages.$.isEdited' => true
							 ]);
		}
	}

	public function getMessageByMessageId($chatId, $messageId)
	{
		$message = $this->message->where('chatId', $chatId)->
			project([
				'messages' => [
					'$elemMatch' => [
						"messageId" => $messageId
					]
				]
		])->first();
		return $message['messages'][0];
	}
}