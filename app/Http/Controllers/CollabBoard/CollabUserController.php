<?php

namespace App\Http\Controllers\CollabBoard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use App\Http\Controllers\ApiController;
use Platform\CollabBoard\Commands\GetInvitedUserCommand;
use Platform\CollabBoard\Transformers\InvitedUserTransformer;
use League\Fractal\Manager;
use Platform\CollabBoard\Commands\LegacyInviteAcceptCommand;
use Platform\Authentication\Transformers\AuthTransformer;
use Platform\CollabBoard\Commands\GoogleInviteAcceptCommand;

class CollabUserController extends ApiController
{
    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

		parent::__construct(new Manager());
    }

    /**
     * Get invoted user details from invite code
     *
     * @param string $collabUrl
     * @param mixed $inviteCode
     */
    public function getInvitedUser($collabUrl, $inviteCode) {
        $user = $this->commandBus->execute(new GetInvitedUserCommand($collabUrl, $inviteCode));
        if ($user) {
            return $this->respondWithItem($user, new InvitedUserTransformer, 'user');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Failed to get user details.');
    }

    /**
     * Signs up a user to collab in good old leagcy way
     *
     * @param Request $request
     * @param string $collabUrl
     * @param string $inviteCode
     */
    public function legacySignup(Request $request, $collabUrl, $inviteCode)
    {
        $user = $this->commandBus->execute(new LegacyInviteAcceptCommand($collabUrl, $inviteCode, $request));
        if ($user) {
            return $this->respondWithItem($user, new AuthTransformer, 'user');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Signup failed. Please try again.');
    }

    /**
     * Signs up a user to collab with google auth
     *
     * @param Request $request
     * @param mixed $collabUrl
     * @param mixed $inviteCode
     * @param mixed $googleToken
     */
    public function googleSignup($collabUrl, $inviteCode, $googleToken)
    {
        $user = $this->commandBus->execute(new GoogleInviteAcceptCommand($collabUrl, $inviteCode, $googleToken));
        if ($user) {
            return $this->respondWithItem($user, new AuthTransformer, 'user');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Signup failed. Please try again.');
    }
}
