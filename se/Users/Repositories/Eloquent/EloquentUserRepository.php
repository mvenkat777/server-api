<?php

namespace Platform\Users\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Repositories\Contracts\UserRepository;

class EloquentUserRepository extends Repository implements UserRepository
{
    const MAX_PIN_LENGTH = 4;

    public function model()
    {
        return 'App\User';
    }

    /**
     * @return mixed
     */
    public function listAllUser($command)
    {
        return $this->model->with('tags')->paginate($command->count);
    }

    /**
     * @param OnboardUser $command
     *
     * @return mixed
     */
    public function createUser(CreateUserCommand $command)
    {
        $confirmationCode = null;
        if (!$command->isActive) {
            $confirmationCode = $this->generateUUID();
        }

        $user = [
            'id' => $this->generateUUID(),
            'display_name' => $command->displayName,
            'email' => $command->email,
            'password' => $command->password ? bcrypt($command->password) : null,
            'confirmation_code' => $confirmationCode,
            'se' => $command->se,
            'is_password_change_required' => $command->isPasswordChangeRequired,
            'is_active' => $command->isActive,
        ];

        return $this->model->create($user);
    }

    /**
     * Verify user and active user account.
     *
     * @param VerifyUserCommand $command
     *
     * @return bool
     */
    public function verifyUser($command, $user)
    {
        $data = [
            'is_active' => true,
        ];

        if (!is_null($command->password)) {
            $data['password'] = bcrypt($command->password);
        }

        return $this->update($data, $user->id);
    }

    public function getByVerificationCode($code)
    {
        return $this->model->where('confirmation_code', $code)->first();
    }

    /**
     * @param GetUserById $command
     *
     * @return mixed
     */
    public function userById($id)
    {
        return $this->model->find($id);
    }

    public function getUserById($id)
    {
        $user = $this->model->where('users.id', '=', $id)
                            ->with('userDetails')
                            ->with('tags')
                            ->with('notes')
                            ->first();

        return $user;
    }

    /**
     * @param UpdateUser $command
     *
     * @return bool
     */
    public function updateUser($data, $userId)
    {
        $user = $this->model->find($userId);

        if ($user) {
            $user->update($data);
            return $this->model->find($userId);
        }
        return false;
    }

    public function updateUserByColumn($column)
    {
        $user = $this->model->where($column['getColumn']['name'], $column['getColumn']['value'])
                            ->update($column['setColumn']);

        return 'success';
    }

    /**
     * Get user based on email.
     *
     * @param string $email
     *
     * @return App\User
     */
    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getUserByEmail($command)
    {
        return $this->model->where('email', $command->email)
        ->join('user_details', 'users.id', '=', 'user_details.user_id')
        ->select('users.email', 'users.display_name', 'user_details.*')
        ->first();
    }

    /**
     * Set Reset Pin of user.
     *
     * @param SendResetPasswordLink $command
     */
    public function setResetPin($user)
    {
        $pin = (int) substr(str_shuffle('0123456789'), 0, self::MAX_PIN_LENGTH);

        $data = [
            'reset_pin' => $pin,
        ];

        $this->update($data, $user->id);

        return $pin;
    }

    /**
     * Change password of user.
     *
     * @param ChangePasswordCommand $command
     * @param App\User              $user
     *
     * @return mixed
     */
    public function changePassword($command, $user)
    {
        $data = [
            'password' => \Hash::make($command->newPassword),
            'is_password_change_required' => false,
        ];

        return $this->update($data, $user->id);
    }

    /**
     * Reset password of user.
     *
     * @param ResetPasswordCommand $command
     *
     * @return No of Columns always 1 (due to user->id)
     */
    public function resetPassword($command, $user)
    {
        $data = [
            'password' => \Hash::make($command->password),
            'reset_pin' => 0,
        ];

        return $this->update($data, $user->id);
    }

    /**
     * Update Password of user and set isPasswordrequired false.
     *
     * @param UpdatePasswordCommand $command
     * @param App\User              $user
     *
     * @return mixed
     */
    public function updatePassword($command, $user)
    {
        $data = [
            'password' => \Hash::make($command->password),
            'is_password_change_required' => false,
        ];

        $this->update($data, $user->id);
        $user->providers()->sync([1], false);

        return $this->getUserById($user->id);
    }

    public function isBanned($id)
    {
        return $this->model->where('id', '=', $id)
                           ->select('is_banned')
                           ->first();
    }

    public function search($se = null, $user = null, $order = null, $count = null)
    {
        if ($se != null && $user == null) {
            if ($se == 'all') {
                return $this->model->where('se', '=', true)->get();
            }

            return $this->model->where('se', '=', true)->paginate($count);
        } elseif ($user != null && $se == null) {
            return $this->model->where('email', 'ILIKE', '%'.$user.'%')
                                ->orWhere('display_name', 'ILIKE', '%'.$user.'%')
                                ->paginate($count);
        } else {
            return $this->model->where('se', '=', true)
                                ->where(function ($query) use ($user) {
                                    $query->where('email', 'ILIKE', '%'.$user.'%')
                                        ->orWhere('display_name', 'LIKE', '%'.$user.'%');
                                })
                                ->paginate($count);
        }
        if ($order != null) {
            return $this->model->orderBy($order, 'desc')->paginate($count);
        }
    }

    public function userBanned($id)
    {
        return $this->model->where('id', '=', $id)
                           ->update(['is_banned' => true]);
    }

    public function userUnBanned($id)
    {
        return $this->model->where('id', '=', $id)
                           ->update(['is_banned' => false]);
    }

    /**
     * Update last login location for user.
     *
     * @param Platform\Authentication\Commands\UpdateLastLoginLocationCommand $command
     *
     * @return mixed
     */
    public function updateLoginLocation($command)
    {
        return $this->model->where('email', $command->email)
                           ->update(
                               ['last_login_location' => $command->location]
                           );
    }

    /**
     * Get both owned and collaborated boards of a user.
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function getBoards($userId)
    {
        return [
            'owner' => $this->getOwnedBoards($userId)->get(),
            'collaborator' => $this->getCollaboratedBoards($userId)->get(),
        ];
    }

    /**
     * Get both owned and collaborated boards with picks of a user.
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function getBoardsWithPicks($userId)
    {
        return [
            'owner' => $this->getOwnedBoards($userId)
                            ->with('fourpicks')
                            ->get(),
            'collaborator' => $this->getCollaboratedBoards($userId)
                                   ->with('fourPicks')
                                   ->get(),
        ];
    }

    /**
     * Get boards(both collaborated and owned) of the user by userId.
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function getOwnedBoards($userId)
    {
        $user = $this->find($userId);

        if (!$user) {
            return;
        }

        return $user->boards()
                    ->having('permission', '=', 'owner');
    }

    /**
     * Get boards on which the user is a collaborator.
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function getCollaboratedBoards($userId)
    {
        $user = $this->find($userId);

        if (!$user) {
            return;
        }

        return $user->boards()
                    ->having('permission', '=', 'collaborator');
    }

    public function filterUser($request)
    {
        $item = isset($request['item']) ? $request['item'] : config('constants.listItemLimit');

        if (isset($request['se'])) {
            if ($request['se'] == 'all') {
                return $this->filter($request)
                            ->where('se', true)
                            ->where('is_banned', false)
                            ->get();
            }
            return $this->filter($request)->paginate($item);
        }
        return $this->filter($request)->paginate($item);
    }
}
