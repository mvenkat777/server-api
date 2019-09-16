<?php
namespace Platform\DirectMessage\Commands;

/**
* To update the message as seen
* UpdateToSeenCommand $data
*/
class UpdateToSeenCommand
{
    public $userId;

    public $chatId;
    
    public $messageId;

    function __construct($data)
    {
        $this->userId = $data['userId'];
        $this->chatId = $data['chatId'];
        $this->messageId = $data['messageId'];
    }
}
