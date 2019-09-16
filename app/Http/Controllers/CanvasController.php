<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Vinkla\Pusher\PusherManager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Canvas\Validators\CanvasValidator;
use Platform\Canvas\Commands\CreateCanvasCommand;
use Platform\Canvas\Commands\CreateCardCommand;
use Platform\Canvas\Commands\CreatePageCommand;
use Platform\Canvas\Commands\GetAllCanvasCommand;
use Platform\Canvas\Commands\GetAllPagesCommand;
use Platform\Canvas\Commands\GetAllCardCommand;
use Platform\Canvas\Commands\GetCanvasByIdCommand;
use Platform\Canvas\Commands\GetCardByIdCommand;
use Platform\Canvas\Commands\GetPageByIdCommand;
use Platform\Canvas\Commands\GetSharedContentCommand;
use Platform\Canvas\Commands\UpdateCanvasCommand;
use Platform\Canvas\Commands\UpdatePageCommand;
use Platform\Canvas\Commands\UpdateCardCommand;
use Platform\Canvas\Commands\StoreNewCommentCommand;
use Platform\Canvas\Commands\GetCommentsCommand;
use Platform\Canvas\Commands\UpdateCommentCommand;
use Platform\Canvas\Commands\StoreCommentReplyCommand;
use Platform\Canvas\Commands\ArchiveCanvasCommand;
use Platform\Canvas\Commands\ArchivePageCommand;
use Platform\Canvas\Commands\ArchiveCardCommand;
use Platform\Canvas\Commands\DeleteMemberCommand;
use Platform\Canvas\Commands\AssignAsManagerCommand;
use Platform\Canvas\Commands\RemoveManagerCommand;
use Platform\Canvas\Commands\JoinPublicGroupCommand;
use Platform\Canvas\Commands\JoinPrivateGroupCommand;

class CanvasController extends ApiController {

	/**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Validation of Canvas
     * @var Platform\Canvas\Validators\CanvasValidator
     */
    protected $canvasValidator;

     /**
     * @var Vinkla\Pusher\PusherManager
     */
    private $pusher;

    /**
     * @param DefalutCommandBus $commandBus
     * @param CanvasValidator $canvasValidator
     * @param CanvasRepository $canvasRepository
     */
    public function __construct(DefaultCommandBus $commandBus,
                                CanvasValidator $canvasValidator,
                                PusherManager $pusher
                                )
    {
        $this->commandBus = $commandBus;
        $this->canvasValidator = $canvasValidator;
        $this->pusher = $pusher;

        parent::__construct(new Manager());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCanvas(Request $request)
    {
        $data = $request->all();
        $this->canvasValidator->setCanvasValidation()->validate($data);
        $result = $this->commandBus->execute(new CreateCanvasCommand($data));
        $collection = $this->respondWithArray(['data' => $result]);
        $userData = json_decode('{}');
        $userData->data = array_values($result);
        if(count($data['members'])){
            foreach ($data['members'] as $key => $value) {
                $this->pusher->trigger(
                        'canvas-'.$value, 
                        'Canvas List', 
                        $userData
                    );
            }
        }
        return $collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePage(Request $request, $canvasId)
    {
    	$data = $request->all();
        $data['canvasId'] = $canvasId;
        $this->canvasValidator->setCanvasValidation()->validate($data);
        $result1 = $this->commandBus->execute(new CreatePageCommand($data, $canvasId));
        $content['pages'] = $result1['page'];
        if(count($result1['page'][0]['sharedPageWith'])){
            $contentId = $result1['page'][0]['sharedPageWith'][0];
            $result1['page'][0]['isAuthenticated'] = [];
            foreach ($contentId['user'] as $counter => $res) {
                $this->pusher->trigger(
                        'canvas-'.$canvasId.'-'.$res['id'], 
                        'Page List', 
                        $content
                    );
                array_push($result1['page'][0]['isAuthenticated'], $res['id']);
            }
            array_push($result1['page'][0]['isAuthenticated'], $content['pages'][0]['owner']['id']);
        }
        // if($result1['page'][0]['_id'] != NULL){
        //     $result = $this->commandBus->execute(new GetAllPagesCommand($canvasId));
        //     $content['totalPages'] = count($result['page']);
        //     $content['canvasTitle'] = $result['canvasTitle'];
        //     $content['pages'] = $result['page'];
        // }
        // dd($content['pages']);
        // foreach ($content['pages'] as $key => $value) {
        //         foreach($value['sharedPageWith'] as $total => $contentId){
        //             foreach ($contentId['user'] as $counter => $res) {
        //                 $content['pages'][$key]['isAuthenticated'] = [];
        //                 $this->pusher->trigger(
        //                         'canvas-'.$canvasId.'-'.$res['id'], 
        //                         'Page List', 
        //                         $content
        //                     );
        //                 array_push($content['pages'][$key]['isAuthenticated'], $res['id']);
        //             }
        //         }
        // }
        $data['pages'] = $result1['page'];
        return $this->respondWithArray(['data' => $data['pages'][0]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCard(Request $request, $canvasId, $pageId)
    {
    	$data = $request->all();
        $data['canvasId'] = $canvasId;
        $data['pageId'] = $pageId;
        $this->canvasValidator->setCardValidation()->validate($data);
        $result = $this->commandBus->execute(new CreateCardCommand($data));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Get all set of canvas from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCanvas()
    {
        $result = $this->commandBus->execute(new GetAllCanvasCommand());
        $data['totalCanvas'] = count($result);
        $data['canvas'] = array_values($result);
        usort($data['canvas'], function($a, $b) { 
            return $a['dateTime']['date'] < $b['dateTime']['date'] ? 1 : -1; 
        });
        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Get all set of pages from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPages($canvasId)
    {
        $result = $this->commandBus->execute(new GetAllPagesCommand($canvasId));
        $data['totalPages'] = count($result['page']);
        $data['canvasTitle'] = $result['canvasTitle'];
        $data['pages'] = $result['page'];
        if(count($data['pages'])){
            foreach ($data['pages'] as $counter => $res) {
                $data['pages'][$counter]['isAuthenticated'] = [];
                foreach ($res['sharedPageWith'] as $key => $value) {
                   foreach ($value['user'] as $checkKey => $content) {
                        array_push($data['pages'][$counter]['isAuthenticated'], $content['id']);   
                    }
                }
                array_push($data['pages'][$counter]['isAuthenticated'], $res['owner']['id']);
            }
        }
        return $this->respondWithArray(['data' => $data,]);
    }

     /* Get all set of cards from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCards($canvasId, $pageId)
    {
        $result = $this->commandBus->execute(new GetAllCardCommand($canvasId, $pageId));
        $data['totalCards'] = count($result);
        $data['cards'] = $result;
        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Get canvas by Id from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCanvasById($canvasId)
    {
        $result = $this->commandBus->execute(new GetCanvasByIdCommand($canvasId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Get canvas by Id from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPageById($canvasId, $pageId)
    {
        $result = $this->commandBus->execute(new GetPageByIdCommand($canvasId, $pageId));
        $data['totalPages'] = count($result['page']);
        $data['canvasTitle'] = $result['canvasTitle'];
        $data['pages'] = $result['page'];
        $data['pages'][0]['isAuthenticated'] = [];
        if(count($result['page'])){
            array_push($data['pages'][0]['isAuthenticated'], $data['pages'][0]['owner']['id']);
            if(count($data['pages'][0]['sharedPageWith'])){
                $contentId = $data['pages'][0]['sharedPageWith'][0];
                foreach ($contentId['user'] as $counter => $res) {
                    array_push($data['pages'][0]['isAuthenticated'], $res['id']);
                }
            }
        }
        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Get canvas by Id from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCardById($canvasId, $pageId, $cardId)
    {
        $result = $this->commandBus->execute(new GetCardByIdCommand($canvasId, $pageId, $cardId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Get shared data list from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSharedContent()
    {
        $result = $this->commandBus->execute(new GetSharedContentCommand());
        $data['totalShared'] = count($result);
        $data['shared'] = $result; 
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Update canvas
     *
     * @param Request $request
     * @param string $canvasId
     * @return \Illuminate\Http\Response
     */
    public function updateCanvas(Request $request, $canvasId)
    {
        $this->canvasValidator->setCanvasValidation()->validate($request->all());
        $updatedCanvas = $this->commandBus->execute(new UpdateCanvasCommand($request->all(), $canvasId));
        return $this->respondWithArray(['data' => $updatedCanvas]);
    }

    /**
     * Update page
     *
     * @param Request $request
     * @param string $canvasId
     * @param string $pageId
     * @return \Illuminate\Http\Response
     */
    public function updatePage(Request $request, $canvasId, $pageId)
    {
        $updatedPage = $this->commandBus->execute(new UpdatePageCommand($request->all(), $canvasId, $pageId));
        return $this->respondWithArray(['data' => $updatedPage]);
    }

    /**
     * Update card
     *
     * @param Request $request
     * @param string $canvasId
     * @param string $pageId
     * @param string $cardId
     * @return \Illuminate\Http\Response
     */
    public function updateCard(Request $request, $canvasId, $pageId, $cardId)
    {
        $updatedCard = $this->commandBus->execute(new UpdateCardCommand($request->all(), $canvasId, $pageId, $cardId));
        return $this->respondWithArray(['data' => $updatedCard]);
    }

    /**
     * Store a new comment resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNewComment(Request $request, $canvasId, $pageId, $cardId)
    {
        $data = $request->all();
        $data['canvasId'] = $canvasId;
        $data['pageId'] = $pageId;
        $data['cardId'] = $cardId;
        $this->canvasValidator->comment()->validate($data);
        $result = $this->commandBus->execute(new StoreNewCommentCommand($data));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Get comments from storage.
     * @return \Illuminate\Http\Response
     */
    public function getComments($canvasId, $pageId, $cardId)
    {
        $result = $this->commandBus->execute(new GetCommentsCommand($canvasId, $pageId, $cardId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Update comment resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateComment(Request $request, $canvasId, $pageId, $cardId, $commentId)
    {
        $data = $request->all();
        $data['canvasId'] = $canvasId;
        $data['pageId'] = $pageId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        $this->canvasValidator->comment()->validate($data);
        $result = $this->commandBus->execute(new UpdateCommentCommand($data));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Store a new comment's reply resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCommentReply(Request $request, $canvasId, $pageId, $cardId, $commentId) {
        
        $data = $request->all();
        $data['canvasId'] = $canvasId;
        $data['pageId'] = $pageId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        $this->canvasValidator->comment()->validate($data);
        $result = $this->commandBus->execute(new StoreCommentReplyCommand($data));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Set canvas to archive.
     *
     * @return \Illuminate\Http\Response
     */
    public function archiveCanvas($canvasId) {
        $result = $this->commandBus->execute(new ArchiveCanvasCommand($canvasId));
        $data['totalCanvas'] = count($result);
        $data['canvas'] = array_values($result);
        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Set page to archive.
     *
     * @return \Illuminate\Http\Response
     */
    public function archivePage($canvasId, $pageId) {
        $result = $this->commandBus->execute(new ArchivePageCommand($canvasId, $pageId));
        $data['totalPages'] = count($result['page']);
        $data['canvasTitle'] = $result['canvasTitle'];
        $data['pages'] = $result['page'];
        return $this->respondWithArray(['data' => $data,]);
    }

    /**
     * Set card to archive.
     *
     * @return \Illuminate\Http\Response
     */
    public function archiveCard($canvasId, $pageId, $cardId) {
        $result = $this->commandBus->execute(new ArchiveCardCommand($canvasId, $pageId, $cardId));
        $data['totalCards'] = count($result);
        $data['cards'] = $result;
        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Delete Page Member. A member can leave a group by itself
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePageMember($canvasId, $pageId) {
        $result = $this->commandBus->execute(new DeleteMemberCommand($canvasId, $pageId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Assign a user as manager 
     *
     * @return \Illuminate\Http\Response
     */
    public function assignAsManager(Request $request, $canvasId, $pageId) {
        $result = $this->commandBus->execute(new AssignAsManagerCommand($request->all(), $canvasId, $pageId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Remove a user from acting as a manager  
     *
     * @return \Illuminate\Http\Response
     */
    public function removeManager(Request $request, $canvasId, $pageId) {
        $result = $this->commandBus->execute(new RemoveManagerCommand($request->all(), $canvasId, $pageId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * To join public canvas page  
     *
     * @return \Illuminate\Http\Response
     */
    public function join($canvasId, $pageId) {
        $result = $this->commandBus->execute(new JoinPublicGroupCommand($canvasId, $pageId));
        return $this->respondWithArray(['data' => $result]);
    }

    /**
     * To join private canvas page  
     *
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request, $canvasId, $pageId) {
        $result = $this->commandBus->execute(new JoinPrivateGroupCommand($request->all(), $canvasId, $pageId));
        return $this->respondWithArray(['data' => $result]);
    }
}
