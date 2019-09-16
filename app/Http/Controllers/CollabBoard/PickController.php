<?php

namespace App\Http\Controllers\CollabBoard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Picks\Commands\UploadNewPickCommand;
use Platform\Picks\Commands\GetAllPicksInABoardCommand;
use Platform\Picks\Commands\GetPickInBoardByIdCommand;
use Platform\Picks\Transformers\PickTransformer;
use Platform\Picks\Commands\FavouritePickCommand;
use Platform\Picks\Commands\RemoveFavouriteFromPickCommand;
use Platform\Picks\Commands\CommentOnPickCommand;
use Platform\Picks\Transformers\PickCommentTransformer;
use Platform\Picks\Commands\DeleteCommentFromPickCommand;
use Platform\Picks\Commands\GetPickCommentsCommand;
use Platform\Picks\Commands\UpdatePickCommand;
use Platform\Picks\Commands\DeletePickCommand;

class PickController extends ApiController
{
    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct(new Manager());
    }

    /**
     * Adds a pick.
     *
     * @param string $boardId
     * @param Request $request
     */
    public function store($boardId, Request $request)
    {
        $pick = $this->commandBus->execute(new UploadNewPickCommand($boardId, $request->all()));
        if ($pick) {
            return $this->respondWithNewItem($pick, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Get all picks inside a board
     *
     * @param string $boardId
     */
    public function index($boardId)
    {
        $picks = $this->commandBus->execute(new GetAllPicksInABoardCommand($boardId));
        if ($picks) {
            return $this->respondWithPaginatedCollection($picks, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     *  Get a pick in a board with id
     *
     * @param string $boardId
     * @param string $pickId
     */
    public function find($boardId, $pickId)
    {
        $pick = $this->commandBus->execute(new GetPickInBoardByIdCommand($boardId, $pickId));
        if ($pick) {
            return $this->respondWithItem($pick, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Update a pick 
     *
     * @param string $boardId
     * @param string $pickId
     */
    public function update($boardId, $pickId, Request $request)
    {
        $pick = $this->commandBus->execute(new UpdatePickCommand($boardId, $pickId, $request->all()));
        if ($pick) {
            return $this->respondWithItem($pick, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Delete a pick 
     *
     * @param string $boardId
     * @param string $pickId
     */
    public function destroy($boardId, $pickId)
    {
        $deleted = $this->commandBus->execute(new DeletePickCommand($boardId, $pickId));
        if ($deleted) {
            return $this->respondOk("Pick deleted.");
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Favourite a pick 
     *
     * @param string $pickId
     */
    public function favourite($pickId)
    {
        $pick = $this->commandBus->execute(new FavouritePickCommand($pickId));
        if ($pick) {
            return $this->respondWithItem($pick, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Remove favourite from a pick 
     *
     * @param string $pickId
     */
    public function removeFavourite($pickId)
    {
        $pick = $this->commandBus->execute(new RemoveFavouriteFromPickCommand($pickId));
        if ($pick) {
            return $this->respondWithItem($pick, new PickTransformer, 'picks');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Add a comment to pick
     *
     * @param string $pickId
     */
    public function addComment($pickId, Request $request)
    {
        $comment = $this->commandBus->execute(new CommentOnPickCommand($pickId, $request->all()));
        if ($comment) {
            return $this->respondWithItem($comment, new PickCommentTransformer, 'pickComment');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Delete a comment form a pick
     *
     * @param string $pickId
     */
    public function deleteComment($pickId, $commentId)
    {
        $deleted = $this->commandBus->execute(new DeleteCommentFromPickCommand($pickId, $commentId));
        if ($deleted) {
            return $this->respondOk("Deleted the comment succesfully");
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Get all comments in a pick
     *
     * @param string $pickid
     */
    public function getComments($pickid)
    {
        $comments = $this->commandBus->execute(new GetPickCommentsCommand($pickid));
        if ($comments) {
            return $this->respondWithPaginatedCollection($comments, new PickCommentTransformer, 'pickComment');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }
}
