<?php
namespace Platform\Collab\Commands;

/**
* GetCommentCardCommand $command
* @return mixed
*/
class GetCommentCardCommand
{
	
	public $collabId;
	public $cardId;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
	}
}