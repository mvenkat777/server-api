<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\CommentRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Helpers\FrameDirectConversation;
use Platform\Collab\Validators\CollabValidator;
use Platform\Collab\Repositories\PermissionRepository;

/**
* CommentCardCommandHandler $command
* @return mixed
*/
class CommentCardCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\CommentRepository
     */
    private $commentRepository;

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

	function __construct(CommentRepository $commentRepository, FrameDirectConversation $frame,
							CollabValidator $validator, PermissionRepository $checkPermission)
	{
		$this->commentRepository = $commentRepository;
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
		if($isPermitted){
			$comment = $this->frame->frameComment($command);
			$isCommented = $this->commentRepository->manipulate($comment, $command->cardId);
			if($isCommented){
				return $this->commentRepository->getCommentById($command->cardId, $comment['id']);
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
			if(isset($value['id']) && ($value['id'] == $auth))
				return true;
		}
		return false;
	}
}