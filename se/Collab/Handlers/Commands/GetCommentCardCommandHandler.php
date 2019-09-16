<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\CommentRepository;
use Platform\Collab\Repositories\ReplyRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\PermissionRepository;

/**
* GetCommentCardCommandHandler $command
* @return mixed
*/
class GetCommentCardCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\CommentRepository
     */
    private $commentRepository;

    /**
     * @var Platform\Collab\Repositories\ReplyRepository
     */
    private $replyRepository;

    /**
     * @var Platform\Collab\Helpers\FrameDirectConversation
     */
    private $frame;

    /**
     * @var Platform\Collab\Repository\PermissionRepository
     */
    private $checkPermission;

	function __construct(CommentRepository $commentRepository, PermissionRepository $checkPermission, ReplyRepository $replyRepository)
	{
		$this->commentRepository = $commentRepository;
		$this->replyRepository = $replyRepository;
		$this->checkPermission = $checkPermission;
	}

	/**
     * @param  StoreNewCollabCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$ifExists = $this->checkPermission->getUserIdByCollab($command->collabId);
		if($ifExists == NULL){
			throw new SeException("Collab Not found", 404,'9002404');
		}	
		$isPermitted = $this->validateIfAuthenticated($ifExists->members, \Auth::user()->id);
		if($isPermitted){
			$comment = $this->commentRepository->getAllComments($command->cardId);
			if($comment){
				return $this->getReply($comment);
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

	public function getReply($comment)
	{
		$data = [];
		foreach ($comment->comment as $key => $value) {
			$data[$key] = $comment->comment[$key];
			$reply = $this->replyRepository->getAllReply($value['id']);
			if($reply){
				$this->concatCommentId($reply->reply, $value['id']);
				$data[$key]['reply'] = $this->concatCommentId($reply->reply, $value['id']);
			}
			else{
				$data[$key]['reply'] = [];
			}
		}
		$comment['comment'] = $data;
		return $comment;
	}

	protected function concatCommentId($reply, $id)
	{
		$data = [];
		foreach ($reply as $key => $value) {
			$data[$key] = $value;
			$data[$key]['commentId'] = $id;
		}
		return $data;
	}
}