<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\DefaultCommandBus;
use Vinkla\Pusher\PusherManager;
use Platform\Collab\Repositories\PermissionRepository;
use Platform\Collab\Repositories\MemberRepository;
use Platform\Collab\Repositories\CardRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\CollabRepository;
use Platform\Collab\Commands\GetAllCollabMembersCommand;
use Carbon\Carbon;

/**
* UpdateCollabMemeberCommand $command
* @return mixed
*/
class UpdateCollabMemeberCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\PermissionRepository
     */
    private $members;

    /**
     * @var Platform\Collab\Repositories\CollabRepository
     */
    private $collabRepository;

    /**
     * @var Platform\Collab\Repositories\MemberRepository
     */
    private $user;

    public function __construct(PermissionRepository $members, 
        MemberRepository $user, 
        CollabRepository $collabRepository, 
        PusherManager $pusher,
        DefaultCommandBus $commandBus,
        CardRepository $cardRepository)
	{
		$this->members = $members;
		$this->user = $user;
		$this->collabRepository = $collabRepository;
        $this->cardRepository = $cardRepository;
        $this->commandBus = $commandBus;
        $this->pusher = $pusher;
	}

	/**
     * @param  UpdateCollabMemeberCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{	
		$memberList = $this->members->getUserIdByCollab($command->collabId);
		$data = $command->userId;
		if($command->set){
			$memberToAdd = array_values(array_diff($data, array_column($memberList->members, 'id')));
			if($memberToAdd){
				if($memberList->isPublic == false){
					$this->validate($data, $memberList->members);
				}
				$details = $this->getUserDetails($memberToAdd);
				if($details){
					$list = $this->members->update($command->collabId, $details);

                    $botData = $this->generateBotMessage($details, $command->collabId);
					$this->cardRepository->manipulate($command->collabId, $botData);

                    $members = $this->commandBus->execute(new GetAllCollabMembersCommand($command->collabId));
                    $card = $this->cardRepository->getCardByCardId($command->collabId, $botData['id']);
                    foreach($members as $member) {
                        $this->pusher->trigger(
                            'collab-'.$command->collabId.'-user-'.$member['id'], 
                            'Collab card',
                            ['data' => $card]
                        );
                    }
				}
			} else{
				throw new SeException("user already exists", 422, '9003422');
			}
		} else {
			$memberToRemove = array_intersect($data, array_column($memberList->members, 'id'));
			if($memberToRemove){
				if($memberList->isPublic == false){
					$this->validate($data, $memberList->members);
				}
				$list = $this->members->remove($command->collabId, $this->userFramedData('id', $data));
			} else {
				throw new SeException("Member not seems to be of this group", 404, '9003404');
			}
		}

		if($list){
			if($command->set){
				$this->user->add($this->structure($this->collabRepository->getByCollabId($command->collabId)), $command->userId);
			} else{
				$this->user->remove($command->collabId, $command->userId);
			}

			$list = $this->members->getUserIdByCollab($command->collabId);
			$members = $list->members;
			$data = [];
			foreach ($members as $key => $value) {
				if(count($value)){
					array_push($data, $value);
				}
			}
			return $data;
		
		} else {
			throw new SeException("Failed to perform action. Try Again", 422, '90010422');
		}
	}

	public function getUserDetails($user)
	{
		$data = [];
		foreach ($user as $key => $value) {
			$data[$key] = $this->userFramedData('id', $value);
			$data[$key]['isOwner'] = false;
			$data[$key]['isManager'] = false;
		}
		return $data;
	}

	public function validate($toRemove, $existingMembers)
	{
		foreach ($existingMembers as $key => $value) {
			if(($value['id'] == $toRemove && $value['isOwner'] == true)){
				throw new SeException("Owner cannnot be removed", 401, '9003401');
			} elseif(($value['id'] == \Auth::user()->id) && ($value['isOwner'] == true || $value['isManager'] == true)){
				return;
			}
		}
		throw new SeException("not authorised to process request", 401, '9004401');
	}

	public function structure($data)
	{
		return [
			'collabId' => $data->id,
			'title' => $data->title,
			'seen' => 0,
			'favourites' => [],
			'isOwner' => false,
			'isManager' => false,
			'isPublic' => $data->isPublic,
			'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
	}

    private function generateBotMessage($users, $collabId)
    {
        $data = '';
        foreach($users as $user) {
            $data .= $user['displayName'].' and ';
        }

        $verb = 'is';
        if(count($users) > 1) {
            $verb = 'are';
        }

        return [
			'id' => $this->generateUUID(),
            'collabId' => $collabId,
            'data' => rtrim($data, ' and ').' '.$verb.' added by '.\Auth::user()->display_name,
            'isEdited' => false,
            'members' => array_column($users, 'id'),
            'isMedia' => false,
			'owner' => [
				'id' => 'SE-BOT',
				'displayName' => 'SE-BOT',
				'email' => 'SE-BOT'
			],
            'type' => 'SE-BOT',
			'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
        ];
    }
}
