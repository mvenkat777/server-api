<?php

namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Collab\Helpers\CollabHelpers;
use Platform\Collab\Helpers\DirectMessageHelpers;
use Platform\Users\Transformers\MetaUserTransformer;

class GetConversationHistoryCommandHandler implements CommandHandler 
{
    protected $directMessagePermissionRepository;
    
    protected $directMessageRepository;

    protected $userRepository;

    protected $collabHelpers;

    public function __construct(DirectMessagePermissionRepository $directMessagePermissionRepository,
                                DirectMessageRepository $directMessageRepository,
                                CollabHelpers $collabHelpers,
                                UserRepository $userRepository)
	{
        $this->directMessagePermissionRepository = $directMessagePermissionRepository;
        $this->directMessageRepository = $directMessageRepository;
        $this->userRepository = $userRepository;
        $this->collabHelpers = $collabHelpers;
	}

	public function handle($command)
	{
        $permission = $this->directMessagePermissionRepository->getDetailsByUserId($command->user->id);
        if($permission) {
            $data = $this->getAllUserDetails($permission->toArray());
            foreach ($data as $key => $value) {
                $chat = $this->directMessageRepository->getGroupConversationHistory($value['chatId']);
                if($chat){
                    $data[$key]['data'] = end($chat->toArray()['messages']);
                } else {
                    $data[$key]['data'] = [];
                }
            }
            return $data;
        }
        return [];
	}

    private function getAllUserDetails(array $permissions)
    {
        $users = [];
        $permissions['chat'] = $this->collabHelpers->sortArray($permissions['chat'], 'lastActivityTime', 'DESC');
        $permissions['group'] = $this->collabHelpers->sortArray($permissions['group'], 'lastActivityTime', 'DESC');
        foreach($permissions['chat'] as $key => $permission) {
            $user = (new MetaUserTransformer)->transform($this->userRepository->getUserById(
                (new DirectMessageHelpers)->regexParticipant($permission['chatId'],
                \Auth::user()->id))
            );
            $user['isGroup'] = false;
            $user['chatId'] = $permission['chatId'];

            if($user) {
                $users[] = $user;
            }
        }

        foreach($permissions['group'] as $key => $permission) {
            $groupUsers = [];
            foreach ($permission['participant'] as $userId) {
                $user = (new MetaUserTransformer)->transform(
                            $this->userRepository->getUserById($userId)
                            );

                if($user) {
                    $groupUsers[] = $user;
                }
            }
            $users[] = ['users' => $groupUsers,
                        'isGroup' => true,
                        'chatId' => $permission['chatId']
                        ];
        }
        return $users;
        return $this->collabHelpers->sortJson($users, 'display_name', 'DESC');
    }

}
