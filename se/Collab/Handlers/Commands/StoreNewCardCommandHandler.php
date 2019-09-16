<?php

namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\PermissionRepository;
use Platform\Collab\Validators\CollabValidator;
use Platform\Collab\Helpers\FrameDirectConversation;
use Carbon\Carbon;

/**
* To Store new card
*/
class StoreNewCardCommandHandler extends Repository implements CommandHandler 
{
	/**
     * @var Platform\Collab\Repositories\CardRepository
     */
    private $cardRepository;

    /**
     * @var Platform\Collab\Helpers\FrameDirectConversation
     */
    private $frame;

    /**
     * @var Platform\Collab\Validators\CollabValidators
     */
    private $validator;

    /**
     * @var Platform\Collab\Repository\PermissionRepository
     */
    private $checkPermission;

	function __construct(CardRepository $cardRepository, FrameDirectConversation $frame,
							CollabValidator $validator, PermissionRepository $checkPermission)
	{
		$this->cardRepository = $cardRepository;
		$this->frame = $frame;
		$this->validator = $validator;
		$this->checkPermission = $checkPermission;
	}

	/**
     * @param  StoreNewCollabCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$ifExists = $this->checkPermission->getUserIdByCollab($command->collabId, $command->members);
		if($ifExists == NULL){
			throw new SeException("Collab Not found", 404,'9002404');
		}	
		$isPermitted = $this->validateIfAuthenticated($ifExists->members, \Auth::user()->id);
		if($isPermitted && $ifExists['isPublic'] == false){
			$command->members = $this->getMemberToAdd($command->members, $ifExists->members);
		} elseif($ifExists['isPublic']) {
			$command->members = $this->getMemberToAdd($command->members, $ifExists->members);
		} else {
			$command->members = [];
		}
		$card = $this->generateCard($command);
		if($card){
			$newCard = $this->cardRepository->manipulate($command->collabId, $card);
			if($newCard){
				if(count($command->members) && $ifExists['isPublic'] == false){
					$this->cardRepository->manipulate($command->collabId, $this->generateBotMessage($command->members));
				} elseif($ifExists['isPublic']){
					$members = $this->getUserId($command->members);
					$isAdded = $this->checkPermission->update($command->collabId, $this->getUserDetails($members));
				}
				return $this->cardRepository->getCardByCardId($command->collabId, $card['id']);
			}		
		} else {
			throw new SeException("Failed to create new card. Try Again", 500, '9004500');
		}
	}

	public function generateCard($command)
	{
		return $this->frame->designForNewCard($command);
	}

	public function validateIfAuthenticated($members, $auth)
	{
		foreach ($members as $key => $value) {
			if(isset($value['id']) && $value['id'] == $auth)
				return true;
		}
		return false;
	}

	public function getMemberToAdd($requested, $exists)
	{
		$taggedMembers = array_values(array_unique($requested));
		$memberToAdd = array_values(array_diff($taggedMembers, array_column($exists, 'email')));
		$user = [];
		foreach ($memberToAdd as $key => $value) {
			$user[$key] = $this->createUserName(\App\User::where('email', $value)->first()->email);
		}
		
		return $user;
	}

	public function generateBotMessage($value)
	{
		return [
			'id' => $this->generateUUID(),
			'data' => 'Want to invite '.$this->convertArrayToString($value).' ?',
			'type' => 'SE-BOT-INVITATION',
			'members' => $value,
			'owner' => [
				'id' => 'SE-BOT-INVITATION',
				'displayName' => 'SE-BOT',
				'email' => 'SE-BOT'
			],
			'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
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
		}
		return $details;
	}
}
