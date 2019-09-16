<?php 
namespace Platform\Collab\Commands;

/**
* CreateCardCommentReplyCommand $command
* @return mixed
*/
class CreateCardCommentReplyCommand
{
	
	public $collabId;
	public $cardId;
	public $commentId;
	public $data;
	public $members;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
		$this->data = $data['data'];
		$this->members = $data['members'];
		$this->commentId = $data['commentId'];
	}
}