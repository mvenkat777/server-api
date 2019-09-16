<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\ReplyRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Validators\CollabValidator;
use Platform\Collab\Repositories\PermissionRepository;

/**
* 
*/
class ArchiveCardCommentReplyCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\ReplyRepository
     */
    private $replyRepository;

    /**
     * @var Platform\Collab\Validators\CollabValidators
     */
    private $validator;

    /**
     * @var Platform\Collab\Repository\PermissionRepository
     */
    private $checkPermission;

	function __construct(ReplyRepository $replyRepository,CollabValidator $validator, 
		PermissionRepository $checkPermission)
	{
		$this->replyRepository = $replyRepository;
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
		$hasCommented = $this->replyRepository->getReplyById($command->commentId, $command->replyId);
		$members = $hasCommented->reply[0]['members'];
		
		if($ifExists == NULL && $hasCommented == NULL){
			throw new SeException("Collab Not found", 404,'9002404');
		}	
		if($hasCommented->owner['id'] == \Auth::user()->id){
			throw new SeException("Not the owner of comment", 1);
		}
		
		$isPermitted = $this->validateIfAuthenticated($ifExists->members, \Auth::user()->id);
		if($isPermitted){
			$isUpdated = $this->replyRepository->remove($command->commentId, $command->replyId);
			if($isUpdated){
				return $this->replyRepository->getAllReply($command->commentId);
			} else {
				throw new SeException("failed to update comment on card. Please try again", 422, '90011422');
			}
		} else {
			throw new SeException("you are not authorized to comment", 401, '9003401');
		}
	}

	public function validateIfAuthenticated($members, $auth)
	{
		foreach ($members as $key => $value) {
			if($value['id'] == $auth)
				return true;
		}
		return false;
	}

	public function updateMember($members, $toAdd, $toRemove)
	{
		foreach (array_values($toAdd) as $key => $value) {
			array_push($members, $value);
		}
		foreach (array_values($toRemove) as $key => $value) {
			if(($id = array_search($value, $members)) !== false) {
			    unset($members[$id]);
			}
		}
		return array_values($members);
	}
}