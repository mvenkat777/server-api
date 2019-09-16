<?php

namespace App\Http\Controllers\CollabBoard;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Boards\Commands\CreateBoardFromAdminPanelCommand;
use Platform\Boards\Commands\GetAllBoardsCommand;
use Platform\Boards\Transformers\BoardsForAdminPageTransformer;

class AdminBoardController extends ApiController
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
     * Get all the boards there.
     */
    public function index()
    {
        $boards = $this->commandBus->execute(new GetAllBoardsCommand());
        if ($boards) {
            return $this->respondWithPaginatedCollection($boards, new BoardsForAdminPageTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }


    /**
     * @param  Request
     */
    public function store(Request $request)
    {
        $board = $this->commandBus->execute(new CreateBoardFromAdminPanelCommand($request->all()));
        if ($board) {
            return $this->respondWithNewItem($board, new BoardsForAdminPageTransformer, 'boards');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }
}
