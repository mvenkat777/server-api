<?php

namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\CollabRepository;
use Platform\Collab\Repositories\MemberRepository;
use Platform\Collab\Repositories\PermissionRepository;
use Platform\Collab\Repositories\Repository;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Collab\Helpers\FrameDirectConversation;
use Platform\Collab\Commands;
use Platform\Collab\Commands\StoreNewCardCommand;

/**
* To Store new[public/private] collab request
*/
class StoreNewCollabCommandHandler extends Repository implements CommandHandler 
{
	/**
     * @var Platform\Collab\Repositories\CollabRepository
     */
    private $collabRepository;

    /**
     * @var Platform\Collab\Repositories\MemberRepository
     */
    private $memberRepository;

    /**
     * @var Platform\Collab\Repositories\PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var Platform\Collab\Helpers\FrameDirectConversation
     */
    private $frame;

	function __construct(CollabRepository $collabRepository, FrameDirectConversation $frame, 
						MemberRepository $memberRepository, PermissionRepository $permissionRepository
						, DefaultCommandBus $commandBus)
	{
		$this->collabRepository = $collabRepository;
		$this->frame = $frame;
		$this->memberRepository = $memberRepository;
		$this->permissionRepository = $permissionRepository;
		$this->commandBus = $commandBus;
	}

	/**
     * @param  StoreNewCollabCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$collab = $this->generateCollab($command);
		if($collab){
			$newCollab = $this->collabRepository->store($collab);
			if($newCollab){
				$command->id = $newCollab->_id;
				array_push($command->members, \Auth::user()->id);
				$members = $this->frame->designForMembers($command);
				$newCollab['collabId'] = $newCollab['_id'];
				$newCollab['isAuthorised'] = true;
				$this->memberRepository->manipulate($members);
				$this->permissionRepository->manipulate($members);
				// $this->queue($members, $this->memberRepository);
				// $this->queue($members, $this->permissionRepository);
				$this->commandBus->execute(new StoreNewCardCommand($this->generateNewCollabBotCard($newCollab)));
				unset($newCollab['_id']);
				return $newCollab;	
			}		
		} else {
			throw new SeException("Failed to create new collab. Try Again", 500, '9004500');
			
		}
	}

	protected function generateNewCollabBotCard($collab)
	{
		return [
			'collabId' => $collab['_id'],
			'data' => \Auth::user()->display_name.' created this collab',
			'isMedia' => false,
			'type' => 'SE-BOT',
			'members' => []
		];
	}

	public function generateCollab($command)
	{
		return $this->frame->designForNewCollab($command);
	}
}