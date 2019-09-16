<?php
namespace Platform\Collab\Repositories;

use Platform\Collab\Models\Comment;

/**
* Responsible for all CRUD actions related to comment
*/
class CommentRepository
{
	
	/**
	 * @var comment
	 */
	protected $comment;

	public function __construct(Comment $comment)
	{
		$this->comment = $comment;
	}

	public function store($cardId)
	{
		$struct = [
			'cardId' => $cardId,
			'comment' => [],
			'total' => 0,
			'archive' => []
		];
		return $this->comment->create($struct);
	}

	public function update($cardId, $data)
	{
		return $this->comment->where('cardId', $cardId)->push('comment', $data);	
	}

	public function updateById($cardId, $commentId, $data)
	{
		return $this->comment->where('cardId', $cardId)
							 ->where('comment.id', $commentId)
							 ->update([
							 	'comment.$.data' => $data->data,
							 	'comment.$.isEdited' => true,
							 	'comment.$.members' => $data->members
							 ]);	
	}

	public function remove($cardId, $commentId)
	{
		return $this->comment->where('cardId', $cardId)
							 ->where('comment.id', $commentId)
							 ->pull('comment',[
							 	'id' => $commentId
							 ]);
	}

	public function getCommentById($cardId, $commentId)
	{
		return $this->comment->where('cardId', $cardId)->
			project([
				'comment' => [
					'$elemMatch' => [
						"id" => $commentId
					]
				]
		])->first();
	}

	public function getAllComments($cardId)
	{
		return $this->comment->where('cardId', $cardId)->first();
	}

	public function manipulate($data, $cardId)
	{
		$isExists = $this->comment->where('cardId', $cardId)->first();
		if($isExists){
			return $this->update($cardId, $data);
		} else {
			$this->store($cardId);
			return $this->update($cardId, $data);
		}
	}
}