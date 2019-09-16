<?php

namespace Platform\Authentication\Validators;

use Platform\App\Exceptions\SeException;
use Platform\Users\Validators\UserValidator;
use Platform\App\Helpers\Helpers;

class AuthValidator
{
    public static function isResetPinSame($command, $user)
    {
        return $command->resetPin == $user->reset_pin;
    }

    public static function isEligible($user)
    {
        if (UserValidator::isUserBanned($user)) {
            throw new SeException(
                'We have some problems. Please contact Admin',
                401,
                3210111
            );
        }

        if (!UserValidator::isSeUser($user) && Helpers::isOriginPlatform()) {
            throw new SeException('You are not allowed here', 401, 3210108);
        }

        return true;
    }

    public static function isSendTasksRequired($user)
    {
        if (is_null($user->last_login_location) && $user->is_password_change_required) {
            return true;
        } else {
            return false;
        }
    }
}
