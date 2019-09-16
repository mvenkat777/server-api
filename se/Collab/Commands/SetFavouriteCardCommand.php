<?php
namespace Platform\Collab\Commands;

/**
* To set a card as favourite
*/
class SetFavouriteCardCommand
{
	/**
	 * @var number collabId
	 */
	public $collabId;

	/**
	 * @var number cardId
	 */
	public $cardId;
	
	/**
	 * @var bool flag
	 */
	public $flag;

	function __construct($data, $flag)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
		$this->flag = $flag;
	}
}