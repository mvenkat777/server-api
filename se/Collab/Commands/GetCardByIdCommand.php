<?php
namespace Platform\Collab\Commands;

/**
* GetCardByIdCommand $command
* @return mixed
*/
class GetCardByIdCommand
{
	public $collabId;
	public $cardId;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
	}
}