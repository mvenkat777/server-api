<?php
namespace Platform\Collab\Commands;

/**
* 
*/
class ArchiveCardCommentReplyCommand
{
	
	public $collabId;
	public $cardId;
	public $commentId;
	public $data;
	public $members;
	public $replyId;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
		$this->data = $data['data'];
		$this->members = $data['members'];
		$this->commentId = $data['commentId'];
		$this->replyId = $data['replyId'];
	}
}