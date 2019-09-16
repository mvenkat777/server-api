<?php

namespace App\Http\Controllers\CollabBoard;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Boards\Commands\ArchiveBoardCommand;
use Platform\Boards\Commands\CreateBoardCommand;
use Platform\Boards\Commands\DestroyBoardCommand;
use Platform\Boards\Commands\GetAllBoardsInACollabCommand;
use Platform\Boards\Commands\GetBoardByIdCommand;
use Platform\Boards\Commands\UnarchiveBoardCommand;
use Platform\Boards\Commands\UpdateBoardCommand;
use Platform\Boards\Transformers\BoardTransformer;
use Platform\Boards\Transformers\BoardsForHomePageTransformer;

class BoardController extends ApiController
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
     * Create a new one here. No boards, no collab.
     *
     * @param Request $request
     * @param mixed $collabUrl
     */
    public function store(Request $request, $collabUrl)
    {
        $board = $this->commandBus->execute(new CreateBoardCommand($collabUrl, $request->all()));
        if ($board) {
            return $this->respondWithNewItem($board, new BoardTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Getting them all. Yes! even the archived ones. No! not the deleted ones.
     *
     * @param string $collabUrl
     */
    public function index($collabUrl)
    {
        $boards = $this->commandBus->execute(new GetAllBoardsInACollabCommand($collabUrl));
        if ($boards) {
            return $this->respondWithPaginatedCollection($boards, new BoardsForHomePageTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Id please?
     *
     * @param string $collabUrl
     * @param string $boardId
     */
    public function find($collabUrl, $boardId)
    {
        $board = $this->commandBus->execute(new GetBoardByIdCommand($collabUrl, $boardId));
        if ($board) {
            return $this->respondWithItem($board, new BoardTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Don't like what you see? Change it. Often.
     *
     * @param Request $request
     */
    public function update($collabUrl, $boardId, Request $request)
    {
        $board = $this->commandBus->execute(new UpdateBoardCommand($collabUrl, $boardId, $request->all()));
        if ($board) {
            return $this->respondWithItem($board, new BoardTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Don't like anything? Delete it then. Less is more.
     *
     * @param mixed $collabUrl
     * @param mixed $boardId
     */
    public function destroy($collabUrl, $boardId)
    {
        $isDestroyed = $this->commandBus->execute(new DestroyBoardCommand($collabUrl, $boardId));
        if ($isDestroyed) {
            return $this->respondOk("Success. Deleted the board.");
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Might like later? Archive it for now.
     *
     * @param mixed $collabUrl
     * @param mixed $boardId
     */
    public function archive($collabUrl, $boardId)
    {
        $board = $this->commandBus->execute(new ArchiveBoardCommand($collabUrl, $boardId));
        if ($board) {
            return $this->respondOk("Success. Archived the board");
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Ah! Already missing them? Unarchive.
     *
     * @param mixed $collabUrl
     * @param mixed $boardId
     */
    public function unarchive($collabUrl, $boardId)
    {
        $board = $this->commandBus->execute(new UnarchiveBoardCommand($collabUrl, $boardId));
        if ($board) {
            return $this->respondWithItem($board, new BoardTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }
}
