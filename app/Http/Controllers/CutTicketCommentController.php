<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Validators\CutTicketCommentValidator;
use Platform\Techpacks\Commands\AddCutTicketCommentCommand;
use Platform\Techpacks\Transformers\CutTicketCommentTransformer;
use League\Fractal\Manager;
use Platform\Techpacks\Commands\DeleteCutTicketCommentCommand;
use Platform\Techpacks\Commands\GetAllCutTicketCommentsCommand;

class CutTicketCommentController extends ApiController
{
    public function __construct(DefaultCommandBus $commandBus, CutTicketCommentValidator $validator) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;

        parent::__construct(new Manager());
    }    

    /**
     * Adds a new comment to Techpack Cut ticket
     *
     * @return mixed
     */
    public function store($techpackId, Request $request) {
        $data = $request->all();
        $data['techpackId'] = $techpackId;
        $data['commentedBy'] = \Auth::user()->id;
        $this->validator->setCreationRules()->validate($data);
        $comment = $this->commandBus->execute(new AddCutTicketCommentCommand($data));

        if ($comment) {
            return $this->respondWithNewItem($comment, new CutTicketCommentTransformer, 'cutticketComment');
        }
        return $this->setStatusCode(500)
                    ->respondError("Failed to add the comment. Please try again.");
    }    

    /**
     * Delete a cutticket comment
     *
     * @param string $commentId
     * @return mixed
     */
    public function destroy($techpackId, $commentId) {
        $deleted = $this->commandBus->execute(new DeleteCutTicketCommentCommand($commentId));

        if ($deleted) {
            return $this->respondOk("Comment successfully deleted.");
        }
        return $this->setStatusCode(500)
                    ->respondError("Failed to delete the comment. Please try again.");
    }    

    /**
     * Get all the cut ticket commets for a techpack
     *
     * @param string $commentId
     * @return mixed
     */
    public function index($techpackId) {
        $comments = $this->commandBus->execute(new GetAllCutTicketCommentsCommand($techpackId));
        return $this->respondWithCollection($comments, new CutTicketCommentTransformer, 'cutticketComment');
    }    
}
