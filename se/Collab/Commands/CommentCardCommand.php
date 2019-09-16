<?php
namespace Platform\Collab\Commands;

/**
* CommentCardCommand $command
* @return $command
*/
class CommentCardCommand
{
	public $collabId;
	public $cardId;
	public $data;
	public $members;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
		$this->data = $data['data'];
		$this->members = $data['members'];
	}
}