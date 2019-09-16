<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Platform\App\Exceptions\SeException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Vinkla\Pusher\PusherManager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Collab\Validators\CollabValidator;
use Platform\Collab\Commands\StoreNewCollabCommand;
use Platform\Collab\Commands\StoreNewCardCommand;
use Platform\Collab\Commands\GetCollabCommand;
use Platform\Collab\Commands\GetDirectMessageCommand;
use Platform\Collab\Commands\StoreNewDirectMessageCommand;
use Platform\Collab\Commands\ArchiveCardCommand;
use Platform\Collab\Commands\ArchiveMessageCommand;
use Platform\Collab\Commands\SetFavouriteMessageCommand;
use Platform\Collab\Commands\ShareMessageCommand;
use Platform\Collab\Commands\UpdateDirectMessageCommand;
use Platform\Collab\Commands\GetAllCollabCommand;
use Platform\Collab\Commands\GetAllCardCommand;
use Platform\Collab\Commands\InviteUserCommand;
use Platform\Collab\Commands\SetFavouriteCardCommand;
use Platform\Collab\Commands\UpdateCardCommand;
use Platform\Collab\Transformers\CollabTransformer;
use Platform\Collab\Commands\GetConversationHistoryCommand;
use Platform\Collab\Commands\GetAllCollabMembersCommand;
use Platform\Collab\Commands\CollabManagerCommand;
use Platform\Collab\Commands\UpdateCollabMemeberCommand;
use Platform\Collab\Commands\CommentCardCommand;
use Platform\Collab\Commands\GetCommentCardCommand;
use Platform\Collab\Commands\UpdateCardCommentCommand;
use Platform\Collab\Commands\ArchiveCardCommentCommand;
use Platform\Collab\Commands\CreateCardCommentReplyCommand;
use Platform\Collab\Commands\UpdateCardCommentReplyCommand;
use Platform\Collab\Commands\ArchiveCardCommentReplyCommand;
use Platform\Collab\Commands\GroupConversationCommand;
use Platform\Collab\Commands\GetCardByIdCommand;
use Platform\Collab\Commands\GetMessageByIdCommand;
use Platform\Collab\Commands\GetMessageByChatIdCommand;
use Platform\Collab\Commands\UpdateUserLastSeenStateCommand;

use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Collab\Repositories\UserStatusRepository;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Carbon\Carbon;
use Auth;
use Platform\Collab\Transformers\CardTransformer;
use Platform\Collab\Helpers\CollabHelpers;

class CollabController extends ApiController {

	/**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Validation of Canvas
     * @var Platform\Canvas\Validators\CollabValidator
     */
    protected $collabValidator;

    protected $userStatusRepo;

     /**
     * @var Vinkla\Pusher\PusherManager
     */
    private $pusher;

    protected $collabHelpers;

    protected $directMessageRepo;

    /**
     * @param DefalutCommandBus $commandBus
     * @param CollabValidator $collabValidator
     * @param CanvasRepository $canvasRepository
     */
    public function __construct(DefaultCommandBus $commandBus,
                                CollabValidator $collabValidator,
                                PusherManager $pusher,
                                CollabHelpers $collabHelpers,
                                UserStatusRepository $userStatusRepo,
                                DirectMessageRepository $directMessageRepo,
                                DirectMessagePermissionRepository $directPermissionRepo)
    {
        $this->commandBus = $commandBus;
        $this->collabValidator = $collabValidator;
        $this->pusher = $pusher;
        $this->userStatusRepo = $userStatusRepo;
        $this->collabHelpers = $collabHelpers;
        $this->directMessageRepo = $directMessageRepo;
        $this->directPermissionRepo = $directPermissionRepo;
        parent::__construct(new Manager());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCollab()
    {
        $result = $this->commandBus->execute(new GetAllCollabCommand());
        $collection = $this->respondWithCollection($result, new CollabTransformer, 'Collab');
        $this->pusher->trigger(
                    'collab-'.\Auth::user()->id, 
                    'Collab List', 
                    $collection
                );
        return $collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCollab(Request $request)
    {
        $data = $request->all();
        $this->collabValidator->setCollabValidation()->validate($data);
        $this->collabValidator->validateMember($data);
        $result = $this->commandBus->execute(new StoreNewCollabCommand($data));
        $this->pusher->trigger(
                    'collab-'.\Auth::user()->id, 
                    'Collab List', 
                    ['data' => $result]
                );

        $result['isAuthorised'] = false;
        foreach ($request->all()['members'] as $key => $value) {
            $this->pusher->trigger(
                    'collab-'.$value, 
                    'Collab List', 
                    ['data' => $result]
                );
        }
        $result['isAuthorised'] = true;
        
        if($result){
            $collection = $this->respondWithArray(['data' => $result]);
            return $collection;
        }
        else
            throw new SeException("Failed to create collab. Try Again", 500);
    }

    public function storeCard(Request $request, $collabId){
        $data = $request->all();
        $this->collabValidator->setCardValidation()->validate($data);
        $data['collabId'] = $collabId;
        $result = $this->commandBus->execute(new StoreNewCardCommand($data));
        $collection = $result['message'];
        $collection[0]['collabId'] = $collabId;
        $collection[0]['isCard'] = true;
        unset($result['message']);

        $members = $this->commandBus->execute(new GetAllCollabMembersCommand($collabId));
        foreach($members as $member) {
            $this->pusher->trigger(
                'collab-'.$collabId.'-user-'.$member['id'], 
                'Collab card',
                ['data' => $collection[0]]
            );
            $collection[0]['newCard'] = true;
            $this->pusher->trigger(
                    'collab-'.$member['id'], 
                    'Collab List', 
                    ['data' => $collection[0]]
                );
        }
        
        if($result)
            return $this->respondWithArray(['data' => $collection[0]]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCard($collabId)
    {
        $result = $this->commandBus->execute(new GetAllCardCommand($collabId));
        if($result['data']){
            $resource = new Collection($result['data'], new CardTransformer, 'Cards');
            $rootScope = $this->fractal->createData($resource)->toArray();
            $data['isAuthorised'] = $result['isAuthorised'];
            $data['data'] = $rootScope['data'];
            return $this->respondWithArray(['data' => $data]);
        }
        else{
            $data['isAuthorised'] = isset($result['isAuthorised'])?$result['isAuthorised']:false;
            return $this->respondWithArray(['data' => $result]);
        }
    }


    /**
     * To get the user specific conversation
     * @param userId
     * @return mixed
     */ 
    public function getUserSpecificConv(Request $request)
    {
        $data = [];
        $data['members'] = $request->all()['members'];
        if(count($data['members']) > 10)
        {
            throw new SeException("Member cannot be more than 10", 422, '9002422');
        }
        $result = $this->commandBus->execute(new GetDirectMessageCommand($data));
        $result['isGroup'] = count($data['members']) > 1;
        // dd();
        // dd($result);
        // $data = $this->collabHelpers->formatDirectMessage($result);
        // dd($data);
        // if($data['isGroup']) {
        //     foreach($data['user'] as $user) {
        //         $participantUser = array_values(array_diff(array_column($data['user'], 'id'), [$user['id']]));
        //         $this->pusher->trigger(
        //             'direct-'.$user['id'],
        //             'Direct List', 
        //             ['data' => $participantUser]
        //         );
        //         $this->pusher->trigger(
        //             'direct-message-'.$data['chatId'], 
        //             'Direct List Message', 
        //             ['data' => $data]
        //         );
        //     }
        // } else{
        //     $this->pusher->trigger(
        //         'direct-'.$this->regexParticipant($data['chatId'], \Auth::user()->id),
        //         'Direct List', 
        //         ['data' => $data['user']]
        //     );
        //     $this->pusher->trigger(
        //         'direct-message-'.$data['chatId'], 
        //         'Direct List Message', 
        //         ['data' => $data]
        //     );
        // }
        if(!$result['isGroup'])
        {
            $result['id'] = $result['user']['id'];
            $result['displayName'] = $result['user']['displayName'];
            $result['email'] = $result['user']['email'];
        }
        if($result['isGroup'])
        {
            $result['users'] = $result['user'];
            unset($result['user']);
        }
        $result['id'] = $result['_id'];
        if(count($result['users']))
        {
            foreach ($result['users'] as $key => $value) {
                $this->pusher->trigger(
                        'direct-'.$value['id'],
                        'Direct List', 
                        ['data' => $result]
                    );
            }
        }
        if($result){
            return $this->respondWithArray(['data' => $result]);
        } else {
            throw new SeException("Failed to fetch history. Try Again", 500);
        }
    }

    /**
     * To store user specific conversation
     * @return mixed
     */ 
    public function storeUserSpecificConv(Request $request)
    {
        $this->collabValidator->setDirectMessageValidation()->validate($request->all());
        $result = $this->commandBus->execute(new StoreNewDirectMessageCommand($request->all()));
        $participantId = $this->regexParticipant($request->all()['convId'], \Auth::user()->id);
        $result['newCard'] = true;
        if(!empty($participantId)) {
            $authObject = \Auth::user();
            $participantUser = [
                'id' => $authObject['id'],
                'displayName' => $authObject['display_name'],
                'email' => $authObject['email'],
                'chatId' => $result['chatId'],
                'isGroup' => false
            ];
            $this->pusher->trigger(
                'direct-'.$participantId,
                'Direct List', 
                ['data' => $result]
            );
            $this->pusher->trigger(
                'direct-message-'.$result['chatId'], 
                'Direct List Message', 
                ['data' => $result]
            );
        } else {
            $userIds = $this->directPermissionRepo->getConversationIdByUserIdOfGroup(\Auth::user()->id, $request->all()['convId'])['participant'];
            $users = [];
            foreach($userIds as $userId) {
                $users[] = (new MetaUserTransformer)->transform(\App\User::find($userId));
            }

            foreach($users as $user) {
                $this->pusher->trigger(
                    'direct-'.$user['id'],
                    'Direct List', 
                    ['data' => $result]
                );
            }
                $this->pusher->trigger(
                    'direct-message-'.$result['chatId'], 
                    'Direct List Message', 
                    ['data' => $result]
                );
        }
        
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to fetch history. Try Again", 500);
    }

    /**
     * Archive a card
     * @return mixed
     */ 
    public function archiveCard($collabId, $cardId)
    {
        $data = [];
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $result = $this->commandBus->execute(new ArchiveCardCommand($data));
        return $result;
    }

    /**
     * Archive a collab
     * @return mixed
     */ 
    public function archiveCollab($collabId)
    {
        $data = [];
        $data['collabId'] = $collabId;
        $result = $this->commandBus->execute(new ArchiveCardCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    /**
     * Archive a message
     * @return mixed
     */ 
    public function archiveMessage($chatId, $messageId)
    {
        $data = [];
        $data['chatId'] = $chatId;
        $data['messageId'] = $messageId;
        $result = $this->commandBus->execute(new ArchiveMessageCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    /**
     * update a message
     * @return mixed
     */
    public function updateDirectMessage(Request $request)
    {
        $data = $request->all();
        if($data['updateKey'] == 'setFavourite') {
            $result = $this->commandBus->execute(new SetFavouriteMessageCommand($data, true));
        } elseif ($data['updateKey'] == 'removeFavourite') {
            $result = $this->commandBus->execute(new SetFavouriteMessageCommand($data, false));
        } elseif ($data['updateKey'] == 'message') {
            $result = $this->commandBus->execute(new UpdateDirectMessageCommand($data));
        }
        return $this->respondWithArray(['data' => $this->collabHelpers->formatDirectMessage($result)]);
    }

    /**
     * update a Collab and its card
     * @return mixed
     */
    public function updateCollabCard(Request $request, $collabId)
    {
        $data = $request->all();
        $data['collabId'] = $collabId;
        
        if($data['updateKey'] == 'setFavourite') {
            $result = $this->commandBus->execute(new SetFavouriteCardCommand($data, true));
        } elseif ($data['updateKey'] == 'removeFavourite') {
            $result = $this->commandBus->execute(new SetFavouriteCardCommand($data, false));
        } elseif ($data['updateKey'] == 'message') {
            $result = $this->commandBus->execute(new UpdateCardCommand($data));
        }

        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    /**
     * share a message
     * @return mixed
     */
    public function shareMessage(Request $request)
    {
        $data = $request->all();
        $convIdOfSharedUser = $this->getUserSpecificConv($data['shareWith']);
        $data['convIdOfSharedUser'] = $convIdOfSharedUser->chatId;
        
        $result = $this->commandBus->execute(new ShareMessageCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    public function inviteUser($collabId, $cardId)
    {
        $result = $this->commandBus->execute(new InviteUserCommand($collabId, $cardId));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to store message. Try Again", 500);
    }

    public function getConvUserHistory()
    {
        $result = $this->commandBus->execute(new GetConversationHistoryCommand());
        return $this->respondWithArray(['data' => $result]);
    }

    public function getCollabMembers($collabId)
    {
        $result = $this->commandBus->execute(new GetAllCollabMembersCommand($collabId));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function assignAsManager($collabId, $userId)
    {
        $result = $this->commandBus->execute(new CollabManagerCommand($collabId, $userId, true));
        $resultPusher['data'] = $result['members'];
        $resultPusher['isUser'] = true;
        foreach ($result['members'] as $key => $value) {
            if(isset($value['id'])){
                $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card',
                    ['data' => $resultPusher]
                );
            }
        }
        if($result)
            return $this->respondWithArray(['data' => $resultPusher]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function removeAsManager($collabId, $userId)
    {
        $result = $this->commandBus->execute(new CollabManagerCommand($collabId, $userId, false));
        $resultPusher['data'] = $result['members'];
        $resultPusher['isUser'] = true;
        foreach ($result['members'] as $key => $value) {
            if(isset($value['id'])){
                $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card',
                    ['data' => $resultPusher]
                );
            }
        }
        if($result)
            return $this->respondWithArray(['data' => $resultPusher]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function addNewMember(Request $request, $collabId)
    {
        $data = [];
        $data = $request->all();
        if(!count($data['members'])){
            throw new SeException("No Member to add", 422, '9002422');
        }   
        $data['collabId'] =$collabId;
        $result = $this->commandBus->execute(new UpdateCollabMemeberCommand($data, true));
        $resultPusher['data'] = $result;
        $resultPusher['isUser'] = true;
        foreach ($result as $key => $value) {
            if(isset($value['id'])){
                $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card',
                    ['data' => $resultPusher]
                );
            }
        }
        if($result)
            return $this->respondWithArray(['data' => $resultPusher]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function removeNewMember(Request $request, $collabId)
    {
        $data = [];
        $data = $request->all();
        if(!count($data['members'])){
            throw new SeException("No Member to remove", 422, '9002422');
        }   
        $data['collabId'] =$collabId;
        $result = $this->commandBus->execute(new UpdateCollabMemeberCommand($data, false));
        $resultPusher['data'] = $result;
        $resultPusher['isUser'] = true;
        foreach ($result as $key => $value) {
            if(isset($value['id'])){
                $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card',
                    ['data' => $resultPusher]
                );
            }
        }
        if($result)
            return $this->respondWithArray(['data' => $resultPusher]);
        else
            throw new SeException("Failed to get member list", 500);
    }
    
    public function updateUserState(){

        $userId = Auth::user()->id;
        $lastUpdatedDateTime = Carbon::now()->toDateTimeString();
        $result = $this->userStatusRepo->update($userId,$lastUpdatedDateTime);
        $exists = array_column($result->toArray(), "userId");
        $history = $this->commandBus->execute(new GetConversationHistoryCommand());
        foreach ($history as $key => $value) {
            if(isset($value["id"]) && in_array($value["id"], $exists)){
                $history[$key]['isOnline'] = true;
            } elseif(isset($value['users'])) {
                foreach ($value['users'] as $count => $data) {
                    if(isset($data["id"]) && in_array($data["id"], $exists)){
                        $history[$key]['users'][$count]['isOnline'] = true;
                    } else {
                        $history[$key]['users'][$count]['isOnline'] = false;
                    }
                };
            } else {
                $history[$key]['isOnline'] = false;
            }
        }
        $this->pusher->trigger(
                    'collab-user'.$userId, 
                    'Collab User Status', 
                    ['data' => $history]
                );
        $result = $this->commandBus->execute(new UpdateUserLastSeenStateCommand($userId));
        $this->pusher->trigger(
                    'unread-'.$userId, 
                    'Collab User Status', 
                    ['data' => $result]
                );
        return $this->respondWithArray(['data' => $history]);
    }

    public function getComment($collabId, $cardId)
    {
        $data = [];
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        
        $result = $this->commandBus->execute(new GetCommentCardCommand($data));
        if($result)
            $result->collabId = $collabId;
        else 
            $result = []; 
        return $this->respondWithArray(['data' => $result]);
    }

    public function storeComment(Request $request, $collabId, $cardId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        
        $result = $this->commandBus->execute(new CommentCardCommand($data));
        $comment = $this->commandBus->execute(new GetCommentCardCommand($data));
        $comment['isComment'] = true;
        $member = $this->commandBus->execute(new GetAllCollabMembersCommand($collabId));
        foreach ($member as $key => $value) {
            $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card', 
                    ['data' => $comment]
                );
        }
        if($result)
            return $this->respondWithArray(['data' => $comment]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function updateComment(Request $request, $collabId, $cardId, $commentId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        
        $result = $this->commandBus->execute(new UpdateCardCommentCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function archiveComment(Request $request, $collabId, $cardId, $commentId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        
        $result = $this->commandBus->execute(new ArchiveCardCommentCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function storeReply(Request $request, $collabId, $cardId, $commentId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        
        $result = $this->commandBus->execute(new CreateCardCommentReplyCommand($data));
        if($result){
            $reply['_id'] = $result['_id'];
            $reply['reply'] = $result['reply'];
            $reply['reply'][0]['commentId'] = $data['commentId'];

            $member = $this->commandBus->execute(new GetAllCollabMembersCommand($collabId));
            $comment = $this->commandBus->execute(new GetCommentCardCommand($data));
            $comment['isComment'] = true;
            foreach ($member as $key => $value) {
                $this->pusher->trigger(
                    'collab-'.$collabId.'-user-'.$value['id'], 
                    'Collab card', 
                    ['data' => $comment]
                );
            }

            return $this->respondWithArray(['data' => $comment]);
        }
        else{
            throw new SeException("Failed to get member list", 500);
        }
    }

    public function updateReply(Request $request, $collabId, $cardId, $commentId, $replyId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        $data['replyId'] = $replyId;
        
        $result = $this->commandBus->execute(new UpdateCardCommentReplyCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function archiveReply(Request $request, $collabId, $cardId, $commentId, $replyId)
    {
        $data = [];
        $data = $request->all();
        
        $this->collabValidator->comment()->validate($data);
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $data['commentId'] = $commentId;
        $data['replyId'] = $replyId;
        
        $result = $this->commandBus->execute(new ArchiveCardCommentReplyCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function storeGroupChat(Request $request)
    {
        $data = [];
        $data = $request->all();
        $this->collabValidator->validateMember($data);
        $result = $this->commandBus->execute(new GroupConversationCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            throw new SeException("Failed to get member list", 500);
    }

    public function getCardById($collabId, $cardId)
    {
        $data = [];
        $data['collabId'] = $collabId;
        $data['cardId'] = $cardId;
        $result = $this->commandBus->execute(new GetCardByIdCommand($data));
        if($result)
            return $this->respondWithArray(['data' => $result]);
    }

    public function getSharedDataByme(Request $request){
        $type = $request->type;
        $isMedia = $request->isMedia;
        $chatId = $request->chatId;
        //dd($type);
        $userId = Auth::user()->id;
        if(isset($type) && $type='platform'){
            $result = $this->directMessageRepo->getPlatformMessagesByUserAndType($chatId,$userId,$type);
        }elseif(isset($isMedia) && $isMedia == true ){
            $result = $this->directMessageRepo->getMediaMessagesByUserAndType($chatId,$userId);
        }       
        
        if($result)
            return $this->respondWithArray(['data' => $result]);
    }

    public function getSharedDataWithme(Request $request){
        $type = $request->type;
        $isMedia = $request->isMedia;
        $chatId = $request->chatId;
        $userId = Auth::user()->id;
        if(isset($type) && $type='platform'){
            $result = $this->directMessageRepo->getPlatformMessagesWithUserAndType($chatId,$userId,$type);
        }elseif(isset($isMedia) && $isMedia == true ){
            $result = $this->directMessageRepo->getMediaMessagesWithUserAndType($chatId,$userId);
        }       
        return $this->respondWithArray(['data' => $result]);
    }

    public function getMessageById($chatId, $messageId)
    {
        $data = [];
        $data['chatId'] = $chatId;
        $data['messageId'] = $messageId;
        $result = $this->commandBus->execute(new GetMessageByIdCommand($data));
        return $this->respondWithArray(['data' => $this->collabHelpers->formatSingleDirectMessage($result)]);
        return $this->respondWithArray(['data' => $result]);
    }

    public function getMessageByChatId($chatId)
    {
        $data = [];
        $data['chatId'] = $chatId;
        $result = $this->commandBus->execute(new GetMessageByChatIdCommand($data));
        $groupStatus = $this->commandBus->execute(new GetConversationHistoryCommand());
        foreach ($groupStatus as $key => $value) {
            if(isset($value['chatId']) && $result['chatId'] == $value['chatId']){
                $result['isGroup'] = $value['isGroup'];
            }
        }
        return $this->respondWithArray(['data' => $this->collabHelpers->formatDirectMessage($result)]);
        // return $this->respondWithArray(['data' => $result]);
    }

    public function regexParticipant($actualChatId, $matchId)
    {
        $participantId = '';
        if(preg_match("/^(.*)$matchId(.*)$/", $actualChatId, $m)) {
                $participantId= $m[1].$m[2];
        }

        return trim($participantId, '-');
    } 
}
