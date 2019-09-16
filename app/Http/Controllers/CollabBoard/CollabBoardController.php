<?php

namespace App\Http\Controllers\CollabBoard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\CollabBoard\Commands\InviteUserToCollabCommand;
use App\Http\Controllers\ApiController;

class CollabBoardController extends ApiController
{
    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function inviteUser(Request $request, $customerId)
    {
        $invited = $this->commandBus->execute(new InviteUserToCollabCommand($customerId, $request->all()));
        if ($invited) {
            return $this->respondOk("User invited to collab.");
        } 
        return $this->respondWithError("Failed to invite user to collab. Please try again.");
    }

}
