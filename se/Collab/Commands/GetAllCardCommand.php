<?php
namespace Platform\Collab\Commands;

/**
* To get all the cards
*/
class GetAllCardCommand
{
	public $collabId;

	function __construct($collabId)
	{
		$this->collabId = $collabId;
	}
}