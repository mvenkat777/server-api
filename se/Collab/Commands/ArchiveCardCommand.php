<?php
namespace Platform\Collab\Commands;

/**
 * This class is to archive a collab
 *
 */
class ArchiveCardCommand {

	/**
	 * @var number collabId
	 */
	public $collabId;

	/**
	 * @var number cardId
	 */
	public $cardId;

	public function __construct($request)
	{
        $this->collabId = $request['collabId'];
        $this->cardId = $request['cardId'];
    }
}