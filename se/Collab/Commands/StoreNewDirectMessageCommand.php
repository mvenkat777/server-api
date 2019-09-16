<?php
namespace Platform\Collab\Commands;

class StoreNewDirectMessageCommand {
	
	/**
	 * Conversation Id
	 * @var long convId
	 */
	public $convId;	

	/**
	 * @var bool isMedia
	 */
	public $isMedia;

	/**
	 * message to send
	 * @var text message
	 */
	public $message;

	/**
	 * conversation type [message | url | note]
	 * @var string type
	 */
	public $type;

	/**
	 * To set as favourite card
	 * @var bool isFavourite
	 */
	public $isFavourite;

	public function __construct($request)
	{
        $this->convId = $request['convId'];
        $this->message = $request['message'];
        $this->type = $request['type'];
        $this->isFavourite = false;
        $this->isMedia = isset($request['isMedia'])?$request['isMedia']:false;
    }		
}