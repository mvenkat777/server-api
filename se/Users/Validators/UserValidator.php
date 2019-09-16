<?php

namespace Platform\Users\Validators;

use App\User;

class UserValidator
{
    /**
     * Check if user created through social login.
     *
     * @param App\User $user
     *
     * @return bool
     */
    public static function isSocialUser(User $user)
    {
        $providers = $user->providers()->get()->pluck('id')->toArray();

        return in_array(2, $providers) || in_array(3, $providers);
    }

    /**
     * Check if user created through legacy signup.
     *
     * @param App\User $user
     *
     * @return bool
     */
    public static function isLegacyUser(User $user)
    {
        $providers = $user->providers()->get()->pluck('id')->toArray();

        return in_array(1, $providers);
    }

    /**
     * Check if current password and database password is same or not.
     *
     * @param string   $password
     * @param App\User $user
     *
     * @return bool
     */
    public static function isSamePassword($password, User $user)
    {
        return $password && \Hash::check($password, $user->password);
    }

    /**
     * Check if a user is sourceeasy employee.
     *
     * @param App\User $user
     *
     * @return bool
     */
    public static function isSeUser($user)
    {
        return $user->se ? true : false;
    }

    /**
     * Check if user is actived his/her account.
     *
     * @param App\User $user
     *
     * @return bool
     */
    public static function isUserActive($user)
    {
        return $user->is_active;
    }

    /**
     * Check if user is banned or not.
     *
     * @param App\User $user
     *
     * @return bool
     */
    public static function isUserBanned($user)
    {
        return $user->is_banned;
    }
}
