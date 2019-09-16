<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\Repository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\MemberRepository;
use Platform\Collab\Repositories\CollabRepository;
use Platform\Collab\Commands\GetAllCardCommand;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Collab\Helpers\CollabHelpers;

/**
* GetAllCollab $command
* To fetch all the collab under a user
*/
class GetAllCollabCommandHandler extends Repository implements CommandHandler
{
	/**
	 * @var \Platform\Collab\Repositories\MemberRepository
	 */
	protected $memberRepository;

	/**
	 * @var \Platform\Collab\Repositories\CollabRepository
	 */
	protected $collabRepository;

	/**
     * @var Platform\Collab\Helpers\CollabHelpers
     */
    protected $collabHelpers;

    public function __construct(CollabHelpers $collabHelpers,CollabRepository $collabRepository,
                                MemberRepository $memberRepository)
	{
        $this->collabHelpers = $collabHelpers;
		$this->memberRepository = $memberRepository;
		$this->collabRepository = $collabRepository;
	}

	/**
     * @param  GetAllCollabCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		/**
		 * To get all collab related to a user 
		 */ 
		$result = $this->memberRepository->getAllByUser(\Auth::user()->id);
		if($result){
			$colabIdList = array_column($result->collab, 'collabId');
		} else {
			$colabIdList = [];
		}
		$publicCollabs = $this->collabRepository->getAllPublicCollab();
		if($publicCollabs){
			$publicId = $publicCollabs->toArray();
			$unCommonId = array_diff(array_column($publicId, '_id'), $colabIdList);
			if($unCommonId){
				$newCollection = isset($result->collab)?$result->collab:[];
				$collection = $this->getUnInvitedCollabData($unCommonId, $publicId, $newCollection);
				return $collection;
			}
			if($result)
				return $this->collabHelpers->formatCollab($result->collab);
			else 
				return [];
		} else {
			return [];
		}
	}

	public function getUnInvitedCollabData($unInvitedCollab, $allPublicCollab, $newCollection)
	{
		$collection = [];
		foreach ($allPublicCollab as $key => $value) {
			foreach ($unInvitedCollab as $count => $id) {
				if($value['_id'] == $id){
					$data = [
						'collabId' => $value['_id'],
						'title' => $value['title'],
						'seen' => 0,
						'favourites' => [],
						'isOwner' => false,
						'isManager' => false,
						'isPublic' => true,
						'isAuthorised' => false,
						'createdAt' => $value['created_at']
					];
					// if(count($newCollection)){
						array_push($newCollection, $data);
					// }
					// else{
					// 	$newCollection = $data;
					// }
				}
			}
		}
		// dd($newCollection);
		return $newCollection;
	}
}
