<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\PermissionRepository;
use Platform\Collab\Repositories\Repository;

/**
* To invite users in a collab handler
*/
class InviteUserCommandHandler extends Repository implements CommandHandler
{
	/**
     * @var Platform\Collab\Repositories\CardRepository
     */
    private $cardRepository;

    /**
     * @var Platform\Collab\Repositories\PermissionRepository
     */
    private $permission;

	function __construct(CardRepository $cardRepository, PermissionRepository $permission)
	{
		$this->cardRepository = $cardRepository;
		$this->permission = $permission;
	}

	/**
     * @param  InviteUserCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$card = $this->cardRepository->getCardByCardId($command->collabId, $command->cardId);
		$cardData = $card->message[0];
		$members = $this->getUserId($cardData['members']);
		if($cardData['type'] == 'SE-BOT-INVITATION'){
			$collabUsers = $this->permission->getUserIdByCollab($command->collabId);
			$id = $this->getMemberToAdd($members, $collabUsers->members);
			if(count($id)){
				$isAdded = $this->permission->update($command->collabId, $this->getUserDetails($id));
				if($isAdded){
					return $this->cardRepository->getAllCards($command->collabId);
				} else {
					throw new SeException("Failed to add user", 422, '9009422');
				}
			} else {
				return $this->cardRepository->getAllCards($command->collabId);
			}

		}
	}

	public function getMemberToAdd($requested, $exists){
		
		$taggedMembers = array_values(array_unique($requested));
		$memberToAdd = array_values(array_diff($taggedMembers, array_column($exists, 'id')));

		return $memberToAdd;
	}

	public function getUserId($user){
		$idCollection = [];
		foreach ($user as $key => $value) {
			$idCollection[$key] = \App\User::where('email',strtolower($value).'@sourceeasy.com')->first()->id;
		}
		return $idCollection;
	}

	public function getUserDetails($id) {
		$details = [];
		foreach ($id as $key => $value) {
			$details[$key] = $this->userFramedData('id', $value);
			$details[$key]['isOwner'] = false;
			$details[$key]['isManager'] = false;
		}
		return $details;
	}
}