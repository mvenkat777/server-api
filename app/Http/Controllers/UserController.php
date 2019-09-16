<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\Users\Commands\AddTagCommand;
use Platform\Users\Commands\AddUserToGroupCommand;
use Platform\Users\Commands\BanUserCommand;
use Platform\Users\Commands\CreateNoteCommand;
use Platform\Users\Commands\CreateUserCommand;
use Platform\Users\Commands\DeleteNoteCommand;
use Platform\Users\Commands\DeleteTagCommand;
use Platform\Users\Commands\GetAllTagCommand;
use Platform\Users\Commands\GetNoteCommand;
use Platform\Users\Commands\GetUserTagCommand;
use Platform\Users\Commands\SearchAllUserCommand;
use Platform\Users\Commands\ShowUserByIdCommand;
use Platform\Users\Commands\UnBanUserCommand;
use Platform\Users\Commands\UpdateNoteCommand;
use Platform\Users\Commands\UserDetailsCommand;
use Platform\Users\Commands\UserSearchCommand;
use Platform\Users\Mailer\UserMailer;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Users\Transformers\UserDetailsTransformer;
use Platform\Users\Transformers\UserNoteTransformer;
use Platform\Users\Transformers\UserTagTransformer;
use Platform\Users\Transformers\UserTransformer;
use Platform\Users\Transformers\UserUserTagTransformer;
use Platform\Users\Validators\SignUp;
use Platform\Users\Validators\UserDetail;
use Platform\Users\Transformers\UserListTransformer;

class UserController extends ApiController
{
    /**
     * @var Platform\Commands\DefaultCommandBus
     */
    protected $commandBus;
    protected $signUp;
    protected $userDetail;
    protected $userMailer;
    protected $userRepository;

    public function __construct(
        DefaultCommandBus $commandBus,
        SignUp $signUp,
        UserMailer $userMailer,
        UserDetail $userDetail,
        UserRepository $userRepository
    ) {
        $this->commandBus = $commandBus;
        $this->userMailer = $userMailer;
        $this->signUp = $signUp;
        $this->userDetail = $userDetail;
        $this->userRepository = $userRepository;

        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $command=new SearchAllUserCommand($request->all());

        $response=$this->commandBus->execute($command);
        return $this->respondWithPaginatedCollection(
            $response, new UserTransformer,
            'user'
        );
    }

    /**
     * Create a new user
     *
     * @param  Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->signUp->validate($request->all());

        $user = $this->commandBus->execute(new CreateUserCommand($request->all()));

        if ($user) {
            return $this->respondWithNewItem($user, new UserTransformer, 'user');
        }

        return $this->statusCode(500)
                    ->respondWithError('User creation failed.', 'SE_50001');
    }

    /**
     * Create user from user management
     *
     * @param  Request $request
     * @return mixed
     */
    public function managementCreateUser(Request $request)
    {
        /*if (!Helpers::isOriginPlatform()) {
            return $this->setStatusCode(401)
                        ->respondWithError('Not authorized to create user.', 'SE_40001');
        }*/
        $host = $_SERVER['HTTP_ORIGIN'];
        $request['admin'] = false;
        $request['isPasswordChangeRequired'] = true;

        $user = $this->commandBus->execute(new CreateUserCommand($request->all()));

        if ($user) {
            return $this->respondWithNewItem($user, new UserTransformer, 'user');
        }

        return $this->statusCode(500)
                    ->respondWithError('User creation failed.', 'SE_50001');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return string
     */
    public function show(Request $request, $id)
    {
        $token = $request->header('access-token');
        $command=new ShowUserByIdCommand($id, $token);
        $response=$this->commandBus->execute($command);

        return $this->respondWithItem($response, new UserTransformer, 'user');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return string
     */
    public function update(Request $request, $id)
    {
        $formData = $request->all();
        $this->userDetail->validate($request->all());
        $command=new UserDetailsCommand($formData, $id);

        $response=$this->commandBus->execute($command);
        if ($response == 'success') {
            $response = 'Successfully Updated';
            return $this->respondOk($response);
        } else {
            return $this->setStatusCode(404)->respondWithError($response);
        }
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $data = $request->all();
        $response = $this->userRepository->filterUser($request->all());
        if (isset($data['se']) && $request->get('se') == 'all') {
            return $this->respondWithCollection(
                $response,
                new UserListTransformer,
                'user'
            );
        }
        return $this->respondWithPaginatedCollection(
            $response,
            new UserListTransformer,
            'user'
        );
    }

    /**
     * @param  Request $request
     * @return string
     */
    public function banUser(Request $request)
    {
        $command = new BanUserCommand($request->get('userId'));
        $response = $this->commandBus->execute($command);

        if ($response == 'success') {
            $response = 'Successfully Banned';
            return $this->respondOk($response);
        } else {
            return $this->setStatusCode(404)
                        ->respondWithError($response);
        }
    }

    /**
     * @param  Request $request
     * @return string
     */
    public function unBannedUser(Request $request)
    {
        $command = new UnBanUserCommand($request->get('userId'));

        $response = $this->commandBus->execute($command);

        if ($response == 'success') {
            $response = 'Successfully Unbanned';
            return $this->respondOk((array)$response);
        } else {
            return $this->setStatusCode(404)
                        ->respondWithError($response);
        }
    }

    /**
     * @param  Request $request
     * @return array
     */
    public function postNote(Request $request)
    {
        $formData = \Input::all();
        $token = $request->header('access-token');
        $command = new CreateNoteCommand($formData, $token);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response, new UserNoteTransformer, 'note');
    }

    /**
     * @param  Request $request
     * @return array
     */
    public function getNote(Request $request, $userId)
    {
        $token = $request->header('access-token');
        $command = new GetNoteCommand($token, $userId);
        $response = $this->commandBus->execute($command);

        return $this->respondWithArray(['data' => $response]);
    }

    /**
     * @param  Request $request
     * @param  User  $id
     * @return string
     */
    public function updateNote(Request $request, $id)
    {
        $formData = \Input::all();
        $token = $request->header('access-token');
        $command = new UpdateNoteCommand($formData, $id, $token);
        $response = $this->commandBus->execute($command);

        if ($response == 'success') {
            $response = 'Successfully Updated';
            return $this->respondOk($response);
        } else {
            return $this->setStatusCode(404)
                        ->respondWithError($response);
        }
    }

    /**
     * @param  Request $request
     * @param  user  $id
     * @return string
     */
    public function deleteNote(Request $request, $id)
    {
        $token = $request->header('access-token');
        $command = new DeleteNoteCommand($id, $token);
        $response = $this->commandBus->execute($command);

        if ($response == 'success') {
            return $this->respondOk('Successfully Deleted');
        }
        return $this->setStatusCode(500)
                    ->respondWithError('Deletion failed.');
    }

    /**
     * @param Request $request
     * @return  collection
     */
    public function addTag(Request $request)
    {
        $formData = $request->all();
        $token = $request->header('access-token');
        $command = new AddTagCommand($formData, $token);
        $response = $this->commandBus->execute($command);

        return $this->respondWithCollection($response, new UserUserTagTransformer, 'userTag');
    }

    /**
     * @param  user $userId
     * @return collection
     */
    public function getUserTag($userId)
    {
        $command = new GetUserTagCommand($userId);
        $response = $this->commandBus->execute($command);

        return $this->respondWithArray(['data' => $response->toArray()]);
    }

    /**
     * @return collection
     */
    public function getAllTag()
    {
        $command = new GetAllTagCommand();
        $response = $this->commandBus->execute($command);

        return $this->respondWithCollection($response, new UserTagTransformer, 'tag');
    }

    /**
     * @param  user $userId
     * @param  userTag $tagId
     * @return string
     */
    public function deleteTag($userId, $tagId)
    {
        $command = new DeleteTagCommand($userId, $tagId);
        $response = $this->commandBus->execute($command);

        if ($response == 'success') {
            $response = 'Successfully Deleted';
            return $this->respondOk($response);
        } else {
            return $this->setStatusCode(404)
                        ->respondWithError($response);
        }
    }
}
