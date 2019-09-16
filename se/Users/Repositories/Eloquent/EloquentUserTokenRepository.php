<?php

namespace Platform\Users\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Repositories\Contracts\UserTokenRepository;

class EloquentUserTokenRepository extends Repository implements UserTokenRepository
{
    public function model()
    {
        return 'App\UserToken';
    }

    public function getByToken($token)
    {
    	return $this->model->where('token','=',$token)->first();

    }
}
