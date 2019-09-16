<?php

namespace Platform\Users\Handlers\Commands;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Helpers\Helpers;
use Platform\Users\Repositories\Contracts\TagRepository;
use Platform\Users\Repositories\Contracts\UserDetailRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Transformers\UserTagTransformer;

class UserSearchCommandHandler implements CommandHandler
{
    

    CONST domain ='@sourceeasy.com';

    /**
     * @var UserRepository
     */
    private $UserRepository;

    /**
     * @var UserDetailRepository
     */
    private $UserDetailRepository;

    /**
    *@var TagRepository
    **/
    private $tagRepository;

    /**
     * @var League\Fractal\Manager
     */
    protected $fractal;

    /**
     * @param UserDetailsRepository
     * @param UserRepository
     * @param TagRepository
     */
    public function __construct(UserRepository $UserRepository,
                                UserDetailRepository $UserDetailRepository,
                                TagRepository $tagRepository)
    {
        $this->UserRepository = $UserRepository;
        $this->UserDetailRepository = $UserDetailRepository;
        $this->tagRepository = $tagRepository;
        $this->fractal = new Manager();
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        if($command->query != NULL)
        {   
            if($command->count != NULL && $command->order != NULL)
            {
                return $this->UserDetailRepository->search($command->query,
                                                             $command->count,
                                                             $command->count);            
            }
            elseif ($command->count != NULL) {
                return $this->UserDetailRepository->search($command->query,
                                                             $command->count);
                
            }
            elseif ($command->order != NULL) {
                return $this->UserDetailRepository->search($command->query,
                                                            $command->count,
                                                            $command->order);
            }
           return $this->UserDetailRepository->search($command->query);            

        }

        if($command->count != NULL)
        {
            if($command->from != NULL)
            {
                return $this->UserDetailRepository->search($command->query, 
                                                            $command->count , 
                                                            $command->order, 
                                                            $command->from);
            }
            return $this->UserDetailRepository->search($command->query,
                                                        $command->count);
        }

        if($command->se != NULL)
        {   
            if($command->data != NULL)
            {
                return $this->UserRepository->search($command->se, 
                                                    $command->data,
                                                    $command->order,
                                                    $command->count);
            }
            return $this->UserRepository->search($command->se,
                                                    $command->data,
                                                    $command->order,
                                                    $command->count );
        }
       
        if($command->order != NULL)
        {
            return $this->UserRepository->search($command->se, 
                                                    $command->data,
                                                    $command->order, 
                                                    $command->count);
        }

        if($command->tag != NULL)
        {   
            $tag = $this->tagRepository->getAll($command);
            $user = $this->tagRepository->search($command);

            $users = new Collection($user, new UserTagTransformer);
            $users = Helpers::paginateTransformer($user, $users);
            if($users['data']){
                $temp = $users['data'][0]['users'];
                $users['data'] = [];
                $users['data']['users'] = $temp;
            }
            $tags = new Collection($tag, new UserTagTransformer);
            $users['data']['tags'] = $this->fractal
                                            ->createData($tags)
                                            ->toArray()['data'];
            return $users;
        }

        if($command->data != NULL)
        {
            return $this->UserRepository->search($command->se,
                                                 $command->data);
        }
        
    }
}
