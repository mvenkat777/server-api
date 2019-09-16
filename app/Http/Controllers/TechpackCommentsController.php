<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Platform\Techpacks\Validators\NewComment;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Commands\AddTechpackCommentCommand;
use Platform\Techpacks\Commands\GetTechpackCommentsCommand;
use Platform\Techpacks\Transformers\TechpackCommentTransformer;

class TechpackCommentsController extends ApiController
{
    private $newComment;
    private $commandBus;

    public function __construct(NewComment $newComment, DefaultCommandBus $commandBus)
    {
        $this->newComment = $newComment;
        $this->commandBus = $commandBus;

        parent::__construct(new Manager());
    }

    /**
     * Returns all the comments fro a particular techpack
     * @param  string $techpackId
     * @return mixed
     */
    public function index($techpackId)
    {
        $comments = $this->commandBus->execute(new GetTechpackCommentsCommand ($techpackId));

        if ($comments) {
            return $this->respondWithCollection($comments, new TechpackCommentTransformer, 'comments');
        } else {
            return $this->respondUnauthorizedError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $techpackId)
    {
        $this->newComment->validate($request->all());

        $comment = $this->commandBus->execute(new AddTechpackCommentCommand($request, $techpackId));

        if ($comment) {
            return $this->respondWithNewItem($comment, new TechpackCommentTransformer, 'comment');
        } else {
            return $this->respondUnauthorizedError();
        }
    }

}
