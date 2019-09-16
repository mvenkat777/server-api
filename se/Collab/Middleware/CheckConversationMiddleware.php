<?php
namespace Platform\Collab\Middleware;

use Platform\Collab\Repositories\DirectMessagePermissionRepository;

/* Acts as a middleware to check whether the conversation exists */
class CheckConversationMiddleware {

	/**
     * @var Platform\Collab\Repositories\DirectMessagePermissionRepository
     */
    private $messagePermissionRepository;

    public function __construct(DirectMessagePermissionRepository $messagePermissionRepository)
	{
		$this->messagePermissionRepository = $messagePermissionRepository;
	}

	/**
	 * To fetch conversationId from DirectMessagePermissionRepository 
     * @var object value
     * @return conversationId
     */
	public function validateIfConversationExists($value)
	{
		$isIdExists =$this->messagePermissionRepository->getConversationIdByUserId($value->initiator, $value->participant);
		if($isIdExists){
			return $isIdExists->chat[$value->participant]['chatId'];
		} else {
			return false;
		}
	}

	public function validateIfGroupConversationExists($value)
	{
		$isIdExists =$this->messagePermissionRepository->getGroupConversationIdByUserId($value->initiator, $value->participant);
		if($isIdExists['group']){
			$id = $value->participant;
			array_push($id, $value->initiator);
			foreach ($isIdExists['group'] as $key => $data) {
				if($id === $data['participant']) {
					return $isIdExists['group'][$key];
				}
			}
			return false;
		} else {
			return false;
		}
	}
}