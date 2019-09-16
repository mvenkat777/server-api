<?php

namespace Platform\Collab\Commands;

/**
* This command is to store all new[ public/private ] collabs
*/
class StoreNewCardCommand
{
	/**
	 * @var string data
	 */
	public $data;

	/**
	 * @var bool isMedia
	 */
	public $isMedia;

	/**
	 * @var text type
	 */
	public $type;

	/**
	 * @var number collabId
	 */
	public $collabId;

	/**
	 * @var array members
	 */
	public $members;

	function __construct($request)
	{
		$this->data = $request['data'];
		$this->type = $request['type'];
		$this->members = $request['members'];
		$this->collabId = $request['collabId'];
		$this->isMedia = isset($request['isMedia'])?$request['isMedia']:false;
	}
}