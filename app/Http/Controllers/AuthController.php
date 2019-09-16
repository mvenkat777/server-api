<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\Apps\Commands\SearchAllPermsCommand;
use Platform\Authentication\Commands\AuthenticateFacebookUserCommand;
use Platform\Authentication\Commands\AuthenticateGoogleUserCommand;
use Platform\Authentication\Commands\AuthenticateUserCommand;
use Platform\Authentication\Commands\LogOutUserCommand;
use Platform\Authentication\Commands\ResetPasswordCommand;
use Platform\Authentication\Commands\SendResetPasswordLinkCommand;
use Platform\Authentication\Commands\UpdateLastLoginLocationCommand;
use Platform\Authentication\Commands\ValidateTokenCommand;
use Platform\Authentication\Commands\VerifyUserCommand;
use Platform\Authentication\Commands\GetUserByTokenCommand;
use Platform\Authentication\Transformers\AuthTransformer;
use Platform\Authentication\Validators\Authenticate;
use Platform\Authentication\Validators\ChangePassword;
use Platform\Authentication\Validators\ResetPassword;
use Platform\Groups\Transformers\GroupTransformer;
use Platform\Roles\Commands\GetAllUserRolesCommand;
use Platform\Roles\Transformers\RoleTransformer;
use Platform\Users\Commands\ChangePasswordCommand;
use Platform\Users\Commands\SetTemporaryPasswordCommand;
use Platform\Users\Commands\UpdatePasswordCommand;
use Platform\Users\Mailer\UserMailer;
use Platform\Users\Validators\UpdatePassword;
use Platform\Dashboard\Commands\GetActivityByScopeCommand;
use Platform\Dashboard\Commands\GetNotificationCommand;
use Platform\Tasks\Commands\GetTaskByTypeCommand;
use Platform\DirectMessage\Commands\GetDirectMessageCommand;
use Platform\DirectMessage\Repositories\PermissionRepository;
use Vinkla\Pusher\PusherManager;
use Platform\Users\Transformers\UserTransformer;

class AuthController extends ApiController
{
    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var Platform\Authentication\Validators\Authenticate
     */
    protected $authenticate;

    /**
     * @var Platform\Authentication\Validators\ChangePassword
     */
    protected $changePassword;

    /**
     * @var Platform\Authentication\Validators\ResetPassword
     */
    protected $resetPassword;

    /**
     * @var Platform\Authentication\Validators\UpdatePassword
     */
    protected $updatePassword;

    /**
     * @var Platform\DirectMessage\Repositories\PermissionRepository
     */
    protected $permissionRepository;

    /**
     * For sending data from pusher
     * @var Vinkla\Pusher\PusherManager
     */
    protected $pusher;

    /**
     * @var Platform\Users\Mailer\UserMailer
     */
    protected $userMailer;

    /**
     * @param DefaultCommandBus $commandBus
     * @param Authenticate $authenticate
     * @param ChangePassword $changePassword
     * @param ResetPassword $resetPassword
     * @param UpdatePassword $updatePassword
     * @param PusherManager $pusher
     * @param PermissionRepository $permissionRepository
     * @param UserMailer $userMailer
     */
    public function __construct(
        DefaultCommandBus $commandBus,
        Authenticate $authenticate,
        ChangePassword $changePassword,
        ResetPassword $resetPassword,
        UpdatePassword $updatePassword,
        PusherManager $pusher,
        PermissionRepository $permissionRepository,
        UserMailer $userMailer
    ) {

        $this->commandBus = $commandBus;
        $this->authenticate = $authenticate;
        $this->changePassword = $changePassword;
        $this->resetPassword = $resetPassword;
        $this->updatePassword = $updatePassword;
        $this->permissionRepository = $permissionRepository;
        $this->pusher = $pusher;
        $this->userMailer = $userMailer;

        parent::__construct(new Manager());
    }

    /**
     * Login user using email and password
     *
     * @param  Request $request
     * @return mixed
     */
    public function legacy(Request $request)
    {
        /*
         * Below function is being used to make email case-insensitive for validators
         */
        $request->merge(['email' => strtolower($request->only('email')['email'])]);
        
        $this->authenticate->validate($request->all());
        $auth = $this->commandBus->execute(new AuthenticateUserCommand($request));

        if (isset($auth->user_id)) {
            $this->commandBus->execute(new UpdateLastLoginLocationCommand($request->email));
            $auth = $this->setRolesGroups($auth);

            $items = is_null($request->get('items')) ? 20 : $request->get('items');
            $auth->activity = $this->commandBus->execute(new GetActivityByScopeCommand('global', $items, 'me'));
            $auth->notification = [];
            $auth->tasks = $this->commandBus->execute(
                new GetTaskByTypeCommand(['type' => 'assigned'])
            );
            $data = [ 'userId' => \Auth::user()->id, 'id' => null];
            $auth->messages = $this->commandBus->execute(new GetDirectMessageCommand($data));
            $auth->messagesCount = $this->permissionRepository->count($data['userId']);
        }


        return $this->respondWithItem($auth, new AuthTransformer, 'auth');
    }

    /**
     * Logs in the user using Google access token
     *
     * @param  string $token
     * @return mixed
     */
    public function google(Request $request, $token)
    {
        $auth = $this->commandBus->execute(new AuthenticateGoogleUserCommand($token));
        if (isset($auth->user_id)) {
            $this->commandBus->execute(new UpdateLastLoginLocationCommand(\Auth::user()->email));
            $auth = $this->setRolesGroups($auth);

            $items = is_null($request->get('items')) ? 20 : $request->get('items');
            $auth->activity = $this->commandBus->execute(new GetActivityByScopeCommand('global', $items, 'me'));
            $auth->notification = [];
            $auth->tasks = $this->commandBus->execute(
                new GetTaskByTypeCommand(['type' => 'assigned'])
            );
            $data = [ 'userId' => \Auth::user()->id, 'id' => null];
            $auth->messages = $this->commandBus->execute(new GetDirectMessageCommand($data));
            $auth->messagesCount = $this->permissionRepository->count($data['userId']);
        }

        return $this->respondWithItem($auth, new AuthTransformer, 'auth');
    }

    /**
     * Logs in user using Facebook access token
     *
     * @param  string $token
     * @return mixed
     */
    public function facebook(Request $request, $token)
    {
        $auth = $this->commandBus->execute(new AuthenticateFacebookUserCommand($token));

        if (isset($auth->user_id)) {
            $this->commandBus->execute(new UpdateLastLoginLocationCommand(\Auth::user()->email));
            $auth = $this->setRolesGroups($auth);

            $items = is_null($request->get('items')) ? 20 : $request->get('items');
            $auth->activity = $this->commandBus->execute(new GetActivityByScopeCommand('global', $items, 'me'));
            $auth->notification = [];
            $auth->tasks = $this->commandBus->execute(
                new GetTaskByTypeCommand(['type' => 'assigned'])
            );
            $data = [ 'userId' => \Auth::user()->id, 'id' => null];
            $auth->messages = $this->commandBus->execute(new GetDirectMessageCommand($data));
            $auth->messagesCount = $this->permissionRepository->count($data['userId']);
        }

        return $this->respondWithItem($auth, new AuthTransformer, 'auth');
    }

    /**
    *@param Confirmation Code
    *@return mixed
    */
    public function verifyAccount($code)
    {
        if ($code == null) {
            return $this->respondWithError('Confirmation code required');
        }

        $password = Helpers::makeTemporaryPassword(time());
        $host = $_SERVER['HTTP_ORIGIN'];
        $user = \App\User::where('confirmation_code', $code)->first();

        if ($this->commandBus->execute(new VerifyUserCommand($code, $password))) {
            $this->userMailer->createdUser(
                $user,
                [
                    'password'=> $password,
                    'url' => $host
                ]
            );
            return $this->setHint('Your account has been successfully activated. Please click the below button to login.')
                        ->respondOk('Successfully Activated.');
        }
        return $this->respondWithError('Something went wrong');
    }

    /**
     * Logs out user
     *
     * @param  Request $request
     * @return json
     */
    public function getLogout(Request $request)
    {
        $token = $request->header('access-token');
        if ($token == null) {
            return $this->setStatusCode(404)->respondWithError("Please Specify token");
        }
        $msg = $this->commandBus->execute(new LogOutUserCommand($token));
        if (!$msg) {
            return $this->setStatusCode(422)->respondWithError("Session Lost. Please Login to Continue");
        } else {
            return $this->respondOk("success");
        }
    }

    /**
     * Change Password of Authenticated User
     *
     * @param  Request $request
     * @return string
     */
    public function changePassword(Request $request)
    {
        $this->changePassword->validate($request->all());

        $result = $this->commandBus->execute(new ChangePasswordCommand($request->all()));
        if ($result == 1) {
            return $this->respondOk('Password Changed');
        } else {
            return $this->respondWithError('Some Problem occurred during password change');
        }
    }

    /**
     * Update Password for logged in user
     *
     * @param  Request $request
     * @return string
     */
    public function updatePassword(Request $request)
    {
        $this->updatePassword->validate($request->all());

        $result = $this->commandBus->execute(new UpdatePasswordCommand($request->all()));

        if ($result) {
            return $this->respondOk('Password Updated');
        } else {
            return $this->respondWithError('Some Problems occurred');
        }
    }

    /**
     * Send reset password link to user email
     *
     * @param  Request $request
     * @return string
     */
    public function sendResetPasswordLink(Request $request)
    {
        $result = $this->commandBus->execute(new SendResetPasswordLinkCommand($request->all()));
        if ($result) {
            return $this->respondOk('Password reset link has been sent to your email');
        } else {
            return $this->respondWithError('Cannot send email');
        }
    }

    /**
     * Reset Password of user
     *
     * @param  Request $request
     * @return string
     */
    public function resetPassword(Request $request)
    {
        $this->resetPassword->validate($request->all());

        $result = $this->commandBus->execute(new ResetPasswordCommand($request->all()));
        if ($result) {
            return $this->respondOk('Your password has been changed');
        } else {
            return $this->respondWithError('Some Problems occurred during resetting password');
        }
    }

    /**
     * Check Login status by the access-token
     *
     * @param Request $request
     * @return string
     */
    public function checkLoginStatus(Request $request)
    {
        $token = $request->header('access-token');
        $validated = $this->commandBus->execute(new ValidateTokenCommand($token));
        if ($validated) {
            return $this->respondOk();
        } else {
            return $this->setStatusCode(401)->respondWithError('Unauthorized', 'SE_3210115');
        }
    }

    /**
     * Get user by token
     *
     * @param Request $request
     * @return string
     */
    public function getUserByToken(Request $request)
    {
        $token = $request->header('access-token');
        $user = $this->commandBus->execute(new GetUserByTokenCommand($token));
        if ($user) {
            return $this->respondWithItem($user, new UserTransformer, 'Auth User');
        } else {
            return $this->setStatusCode(401)->respondWithError('Unauthorized', 'SE_3210115');
        }
    }

    public function getAllPerms(){

        $command=new SearchAllPermsCommand();
        $allperms=$this->commandBus->execute($command);

        $finalPerm = [];
        foreach($allperms as $perms){
            $finalPerm[$perms['id']] = $perms['permission'];
        }

        return $finalPerm;
    }

    public function fetchUserRolesPermissions($uid){

        $cmdResponse = $this->commandBus->execute(new GetAllUserRolesCommand($uid));
        $rolesPermissions = new Collection($cmdResponse, new RoleTransformer, 'Role');
        $rolesPermissions = $this->fractal->createData($rolesPermissions)->toArray();

        $roleNames=[];
        $apps_permission = [];
        foreach($rolesPermissions['data'] as $roleData){
            $roleNames[] = $roleData['roleName'];
            foreach($roleData['appPermission'] as $appData){
                $appSlug = $this->formatSlug($appData->name);
                if(!isset($apps_permission[$appSlug])){
                    $apps_permission[$appSlug] = [];
                }

                $apps_permission[$appSlug]['appName'] = $appData->name;
                $apps_permission[$appSlug]['appSlug'] = $appSlug;
                $apps_permission[$appSlug]['show'] = true;

                //$apps_permission[$appSlug]['permissions'] = (array)$appData->permissions;
                if(!isset($apps_permission[$appSlug]['permissions'])){
                    $apps_permission[$appSlug]['permissions'] = [];
                }

                $apps_permission[$appSlug]['permissions'] = $this->mergePermissions($apps_permission[$appSlug]['permissions'] , (array)$appData->permissions);
            }

        }

        $apps_permission = $this->convertArrayToObject($apps_permission);
        //dd($apps_permission);
        $rolesPermissions['data'] = ['roles'=> $roleNames,
                                     'apps_permissions'=> $apps_permission
                                    ];

        return $rolesPermissions['data'];

    }

    public function mergePermissions($slugPerms,$perms){
        foreach($perms as $permName => $bit ){
            if (!isset($slugPerms[$permName])) {
                $slugPerms[$permName] = false;
            }
            $slugPerms[$permName] = $slugPerms[$permName] || $bit;
        }
        return $slugPerms;

    }

    public function convertArrayToObject($apps_permission){

        $apps_permission = array_values($apps_permission);
        $apps_permission = json_decode(json_encode($apps_permission));

        return $apps_permission;
        // $finalAppPerm = [];
        // foreach($apps_permission as $slug => $mainarr){
        //     $finalAppPerm[] = (object)$mainarr;
        // }
        // return $finalAppPerm;
    }

    public function formatSlug($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        return $string;
}

    // public function fetchUserGroups($uid){
    //     $cmdResponse = $this->commandBus->execute(new GetAllUserGroupsCommand($uid));
    //     $resourceGroups = new Collection($cmdResponse, new GroupTransformer, 'Group');
    //     $Groups = $this->fractal->createData($resourceGroups)->toArray();
    //     return $Groups['data'];
    //     //return $this->respondWithCollection($cmdResponse , new GroupTransformer , 'Group');
    // }

    public function setRolesGroups($auth)
    {
        //dd($auth->toArray());
        if ($auth->user_id != null) {
            $completeData = $this->fetchUserRolesPermissions($auth->user_id);
            //dd($completeData);
            $auth->roles =  $completeData['roles'];
            $auth->appsPermissions =  $completeData['apps_permissions'];
            //$auth->groups =  $this->fetchUserGroups($auth->user_id);
        }
        //dd($auth);
        return $auth;
    }

    /*
    * UserPusher is defined for online/offline feature on platform
    * PusherManager $pusher
    */
    public function userStatus()
    {
        $user = \Auth::user();
        $userDetail = ['id' => $user->id, 'displayName' => $user->display_name, 'email' => $user->email];
        $this->pusher->trigger(
                'PlatformUserStatus', 
                'Connect User', 
                ['channel_data' => $userDetail]
            );
        return $this->respondWithArray(['data' => $userDetail]);
    }
}
