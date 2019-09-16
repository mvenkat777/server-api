<?php
namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\Reply;

/**
* To perform all the CRUD opeation of Reply
*/
class ReplyRepository extends Repository
{
	/**
	 * @var reply
	 */
	protected $reply;

	public function __construct(Reply $reply)
	{
		$this->reply = $reply;
	}

	public function store($cardId)
	{
		$struct = [
			'commentId' => $cardId,
			'reply' => [],
			'total' => 0,
			'archive' => []
		];
		return $this->reply->create($struct);
	}

	public function update($commentId, $data)
	{
		return $this->reply->where('commentId', $commentId)->push('reply', $data);	
	}

	public function updateById($commentId, $replyId, $data)
	{
		return $this->reply->where('commentId', $commentId)
							 ->where('reply.id', $replyId)
							 ->update([
							 	'reply.$.data' => $data->data,
							 	'reply.$.isEdited' => true,
							 	'reply.$.members' => $data->members
							 ]);	
	}

	public function remove($commentId, $replyId)
	{
		return $this->reply->where('commentId', $commentId)
							 ->where('reply.id', $replyId)
							 ->pull('reply',[
							 	'id' => $replyId
							 ]);
	}

	public function getReplyById($commentId, $replyId)
	{
		return $this->reply->where('commentId', $commentId)->
			project([
				'reply' => [
					'$elemMatch' => [
						"id" => $replyId
					]
				]
		])->first();
	}

	public function getAllReply($commentId)
	{
		return $this->reply->where('commentId', $commentId)->first();
	}

	public function manipulate($data, $commentId)
	{
		$isExists = $this->reply->where('commentId', $commentId)->first();
		if($isExists){
			return $this->update($commentId, $data);
		} else {
			$this->store($commentId);
			return $this->update($commentId, $data);
		}
	}
}