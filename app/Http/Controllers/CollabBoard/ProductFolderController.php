<?php

namespace App\Http\Controllers\CollabBoard;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Boards\Commands\CommentOnProductFolderCommand;
use Platform\Boards\Commands\CreateNewProductFolderCommand;
use Platform\Boards\Commands\DeleteCommentFromProductFolderCommand;
use Platform\Boards\Commands\DeleteProductFolderCommand;
use Platform\Boards\Commands\GetAllProductFoldersCommand;
use Platform\Boards\Commands\GetProductFolderByIdCommand;
use Platform\Boards\Commands\GetProductFolderCommentsCommand;
use Platform\Boards\Commands\UpdateProductFolderCommand;
use Platform\Boards\Transformers\ProductFolderCommentTransformer;
use Platform\Boards\Transformers\ProductFolderForSidebarTransformer;
use Platform\Boards\Transformers\ProductFolderTransformer;

class ProductFolderController extends ApiController
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
     * Creates a new board
     *
     * @param string $boardId
     * @param Request $request
     */
    public function store($boardId, Request $request)
    {
        $productFolder = $this->commandBus->execute(new CreateNewProductFolderCommand($boardId, $request->all()));
        if ($productFolder) {
            return $this->respondWithNewItem($productFolder, new ProductFolderTransformer, 'productFolder');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Get all the product folders ina board
     *
     * @param mixed $boardId
     */
    public function index($boardId)
    {
        $productFolders = $this->commandBus->execute(new GetAllProductFoldersCommand($boardId));
        if ($productFolders) {
            return $this->respondWithPaginatedCollection($productFolders, new ProductFolderForSidebarTransformer, 'productFolder');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Get a single product folder
     *
     * @param string $boardId
     * @param string $productFolderId
     */
    public function find($boardId, $productFolderId)
    {
        $productFolder = $this->commandBus->execute(new GetProductFolderByIdCommand($boardId, $productFolderId));
        if ($productFolder) {
            return $this->respondWithItem($productFolder, new ProductFolderTransformer, 'productFolder');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Update a product folder
     *
     * @param string $boardId
     * @param string $productFolderId
     */
    public function update($boardId, $productFolderId, Request $request)
    {
        $productFolder = $this->commandBus->execute(new UpdateProductFolderCommand($boardId, $productFolderId, $request->all()));
        if ($productFolder) {
            return $this->respondWithItem($productFolder, new ProductFolderTransformer, 'productFolder');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Delete a product folder
     *
     * @param string $boardId
     * @param string $productFolderId
     */
    public function destroy($boardId, $productFolderId, Request $request)
    {
        $deleted = $this->commandBus->execute(new DeleteProductFolderCommand($boardId, $productFolderId, $request->all()));
        if ($deleted) {
            return $this->respondOk("Deleted the product folder.");
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Add a comment to pick
     *
     * @param string $pickId
     */
    public function addComment($productFolderId, Request $request)
    {
        $comment = $this->commandBus->execute(new CommentOnProductFolderCommand($productFolderId, $request->all()));
        if ($comment) {
            return $this->respondWithItem($comment, new ProductFolderCommentTransformer, 'pickComment');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }

    /**
     * Delete a comment form a pick
     *
     * @param string $pickId
     */
    public function deleteComment($productFolderId, $commentId)
    {
        $deleted = $this->commandBus->execute(
            new DeleteCommentFromProductFolderCommand($productFolderId, $commentId)
        );
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
    public function getComments($productFolderId)
    {
        $comments = $this->commandBus->execute(new GetProductFolderCommentsCommand($productFolderId));
        if ($comments) {
            return $this->respondWithPaginatedCollection(
                $comments,
                new ProductFolderCommentTransformer, 'pickComment'
            );
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Sorry. Something went wrong.");
    }
}
