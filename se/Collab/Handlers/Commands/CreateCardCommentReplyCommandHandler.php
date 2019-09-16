<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Helpers\FrameDirectConversation;
use Platform\Collab\Validators\CollabValidator;
use Platform\Collab\Repositories\PermissionRepository;
use Platform\Collab\Repositories\ReplyRepository;

/**
* CreateCardCommentReplyCommandHandler $command
* @return mixed
*/
class CreateCardCommentReplyCommandHandler extends Repository implements CommandHandler
{
	
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

    /**
     * @var Platform\Collab\Repository\ReplyRepository
     */
    private $replyRepository;

	function __construct(FrameDirectConversation $frame, CollabValidator $validator, 
				PermissionRepository $checkPermission, ReplyRepository $replyRepository)
	{
		$this->frame = $frame;
		$this->validator = $validator;
		$this->checkPermission = $checkPermission;
		$this->replyRepository = $replyRepository;
	}

	/**
     * @param  CreateCardCommentReplyCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$ifExists = $this->checkPermission->getUserIdByCollab($command->collabId, $command->members);
		if($ifExists == NULL){
			throw new SeException("Collab Not found", 404,'9002404');
		}	
		$isPermitted = $this->validateIfAuthenticated($ifExists->members, \Auth::user()->id);
		if($isPermitted){
			$comment = $this->frame->frameReply($command);
			$isReplied = $this->replyRepository->manipulate($comment, $command->commentId);
			if($isReplied){
				return $this->replyRepository->getReplyById($command->commentId, $comment['id']);
			} else {
				throw new SeException("failed to comment on card. Please try again", 422, '90011422');
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
}
