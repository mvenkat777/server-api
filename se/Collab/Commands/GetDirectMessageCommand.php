<?php
namespace Platform\Collab\Commands;

/* To Store the direct conversation between two users */
class GetDirectMessageCommand {

	/**
	 * Chat Initiator user's UserId
	 * Auth user
	 * @var string initiator
	 */
	public $initiator;

	/**
	 * Participating user's UserId
	 * @var string $participant
	 */
	public $participant;

	public function __construct($data)
	{
        $this->initiator = \Auth::user()->id;
        $this->participant = $data['members'];
    }
}