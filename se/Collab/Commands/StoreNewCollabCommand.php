<?php

namespace Platform\Collab\Commands;

/**
* This command is to store all new[ public/private ] collabs
*/
class StoreNewCollabCommand
{
	/**
	 * @var string title
	 */
	public $title;

	/**
	 * @var text description
	 */
	public $description;

	/**
	 * @var bool isPublic
	 */
	public $isPublic;

	/**
	 * @var array members
	 */
	public $members;

	function __construct($request)
	{
		$this->title = $request['title'];
		$this->description = $request['description'];
		$this->isPublic = $request['isPublic'];
		$this->members = $request['members'];
	}
}