<?php
namespace Platform\Collab\Commands;

/**
* To get all the members of a collab
*/
class GetAllCollabMembersCommand
{
	/**
	 * @var collabId
	 */
	public $collabId;
	
	function __construct($collabId)
	{
		$this->collabId = $collabId;
	}
}