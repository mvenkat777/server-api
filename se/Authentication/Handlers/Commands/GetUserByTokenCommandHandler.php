<?php

namespace Platform\Authentication\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class GetUserByTokenCommandHandler implements CommandHandler 
{
    /**
     * @param UserTokenRepository $tokenRepository
     **/
    public function __construct(UserTokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function handle($command)
    {
        $result = $this->tokenRepository->getIdByToken($command->token);
        if($result) {
            return $result->user;
        } else {
            return false;
        }
    }
                    
}
