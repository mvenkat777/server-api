<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Platform\Boards\Repositories\Contracts\BoardRepository;
use Platform\Users\Validators\UserValidator;

class QuoteController extends Controller
{
    /**
     * @var Platform\Boards\Repositories\Contracts\BoardRepository
     */
    protected $board;

    public function __construct(BoardRepository $board)
    {
        $this->board = $board;
    }

    /**
     * Create a request for a quote
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request = $request->all();
        if ($this->board->isOwner($request->boardId, Auth::user()->id)) {
            // request for quote
        }

        return $this->respondUnauthorizedError();
    }

    /**
     * Get all the quote requests(for se user) only his for other users
     *
     * @return mixed
     */
    public function index()
    {
        $request = $request->all();
        if (UserValidator::isSeUser(Auth::user())) {
            // return all the quote requests
        }

        $quotes; // get logged in users quote requests

        return $this->respondInternalError();
    }
}
