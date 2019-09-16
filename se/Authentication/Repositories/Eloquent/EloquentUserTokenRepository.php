<?php

namespace Platform\Authentication\Repositories\Eloquent;

use App\ActivityModel\NotificationModel\NotifyTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Authentication\Repositories\Contracts\UserTokenRepository;

class EloquentUserTokenRepository extends Repository implements UserTokenRepository
{
    public function model()
    {
        return 'App\UserToken';
    }

    /**
     * @param  AuthenticateUserCommand
     *
     * @return mixed
     */
    public function authenticateUser($command)
    {
        if (Auth::once(
            ['email' => $command->email, 'password' => $command->password]
        )) {
            $token = bcrypt(Auth::user()->id . time());
            $auth = [
                'user_id' => Auth::user()->id,
                'token' => $token,
                'expires_at' => Carbon::now()->addDays(7),
            ];
            $auth = $this->create($auth);
            $auth = $this->find($auth->id);
            $auth->user = Auth::user();
            $auth->providers = Auth::user()->providers()->lists('id');
            //$auth->activity = $this->getActivityTargetMessage(Auth::user()->email);
            return $auth;
        }

        throw new SeException(
            'Email/Password combination is wrong',
            401,
            3210100
        );
    }

    /**
     * Authenticate google/facebook user.
     *
     * @param UserRepository $user
     *
     * @return mixed
     */
    public function authenticateSocialUser($id)
    {
        if (Auth::loginUsingId($id)) {
            $token = bcrypt(Auth::user()->id);
            $auth = [
                'user_id' => Auth::user()->id,
                'token' => $token,
                'expires_at' => Carbon::now()->addDays(7),
            ];
            $auth = $this->model->create($auth);
            $auth = $this->model->find($auth->id);
            $auth->user = Auth::user();
            //$auth->activity = $this->getActivityTargetMessage(Auth::user()->email);
            return $auth;
        }

        throw new SeException('Invalid Credentials"', 401, 3210003);
    }

    /**
     * Logout user.
     *
     * @param LogOutUserCommand $command
     *
     * @return bool
     */
    public function logOutUser($command)
    {
        $auth = $this->findBy('token', $command->token);
        if ($auth) {
            $auth->delete();

            return true;
        }

        throw new SeException(
            'Session lost. Please login again.',
            401,
            3210103
        );
    }

    /**
     * Validate token is present or not.
     *
     * @param ValidateTokenCommand $command
     *
     * @return bool
     */
    public function validateAuthToken($command)
    {
        $auth = $this->findBy('token', $command->token);
        if ($auth && $auth->expires_at > Carbon::now()) {
            Auth::login($auth->user);

            return true;
        }

        return false;
    }

    /**
     * @param Access token
     *
     * @return mixed
     */
    public function getIdByToken($token)
    {
        return $this->model->where('token', '=', $token)->first();
    }

    /**
     * @param  User Id
     *
     * @return 1
     */
    public function deleteByUserId($userId)
    {
        return $this->model->where('user_id', '=', $userId)->delete();
    }

    /**
     * Get activity messages for logging in user
     * @param  string $email
     * @return mixed
     */
    public function getActivityTargetMessage($email)
    {
        return NotifyTarget::select('target.message')
                    ->where('actionObject.email', $email)
                    ->limit(30)
                    ->get();
    }
}
