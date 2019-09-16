<?php

namespace Platform\Users\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\TagUserRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

class AddTagCommandHandler implements CommandHandler
{
    /**
     * @var TagRepository
     */
    private $tagRepo;

    /**
     * @var TagUserRepository
     */
    private $tagUserRepo;

    /**
     * @var UserTokenRepository
     */
    private $tokenRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @param Platform\Users\Repositories\Contracts\TagRepository       $tagRepo     
     * @param Platform\Users\Repositories\Contracts\TagUserRepository   $tagUserRepo 
     * @param Platform\Authentication\Repositories\Contracts\UserTokenRepository $tokenRepo   
     * @param Platform\Users\Repositories\Contracts\UserRepository      $userRepo    
     */
    public function __construct(
        TagRepository $tagRepo , 
        TagUserRepository $tagUserRepo,
        UserTokenRepository $tokenRepo,
        UserRepository $userRepo
    ) {
        $this->tagRepo = $tagRepo;
        $this->tagUserRepo = $tagUserRepo;
        $this->tokenRepo = $tokenRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @param  AddTagCommand
     * @return mixed
     */
    public function handle($command)
    {
        foreach ($command->userId as $key => $userId) 
        {
           
            $taggedBy = $this->tokenRepo->getIdByToken($command->token);
            $tag = $this->tagRepo->getTag($command->name);
            $user = $this->userRepo->find($userId);
            if(!is_null($tag) && !is_null($user))
            {
                $user->tags()
                     ->sync([$tag->id => ['tagged_by' => $taggedBy->user_id]], false);
                $user->recordCustomActivity($user, ['tags', [$tag->id], false, NULL], 'add');

                $data[$key] = $this->tagUserRepo->getTagUser($userId , $tag->id); 
            }
            else if($user)
            {
                $tag = $this->tagRepo->createTag($command);
                $user->tags()
                     ->sync([$tag->id => ['tagged_by' => $taggedBy->user_id]], false);
                $user->recordCustomActivity($user, ['tags', [$tag->id], false, NULL], 'add');

                $data[$key] = $this->tagUserRepo->getTagUser($userId , $tag->id); 

            }
        }
        return $data;
	}
}