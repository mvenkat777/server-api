<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Roles\Commands\CreateRoleCommand;
use Platform\Roles\Commands\SearchAllRoleCommand;
use Platform\Roles\Commands\ShowRoleByIdCommand;
use Platform\Roles\Commands\UpdateRoleCommand;
use Platform\Roles\Commands\DeleteRoleCommand;
use Platform\Roles\Commands\SearchRolesByGroupCommand;
use Platform\Roles\Repositories\Eloquent\EloquentRoleUserRepository;
use Platform\Authentication\Repositories\Eloquent\EloquentUserTokenRepository;
use Platform\Roles\Transformers\RoleTransformer;
use Platform\Users\Transformers\UserTransformer;
use League\Fractal\Manager;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\Roles\Jobs\UserLogoutIntimationJob;

class RoleController extends ApiController
{

    /**
     * @var Platform\Commands\DefaultCommandBus
     */
    protected $commandBus;
    protected $roleuserRepo;
    protected $userTokenRepo;


    function __construct(DefaultCommandBus $commandBus,
                         EloquentRoleUserRepository $roleuserRepo,
                         EloquentUserTokenRepository $userTokenRepo)
    {
        $this->roleuserRepo = $roleuserRepo;
        $this->userTokenRepo = $userTokenRepo;
        $this->commandBus = $commandBus;

        parent::__construct(new Manager());
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $command=new SearchAllRoleCommand();
        $response=$this->commandBus->execute($command);
        return $this->respondWithCollection($response, new RoleTransformer, 'Role');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $formData=$request->all();
        $formData['token'] = $request->header('access-token');

        $response=$this->commandBus->execute(new CreateRoleCommand($formData));
        return $this->setMessage('Role created successfully.')
                    ->respondWithNewItem($response, new RoleTransformer, 'Role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $response = $this->commandBus->execute(new ShowRoleByIdCommand($id));
        if ($response != null) {
            return $this->respondWithItem($response, new RoleTransformer, 'Role');
        } else {
            return $this->setStatusCode(404)
                        ->respondWithError('Role not found', 'SE_40004');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $formData=$request->all();
        $response=$this->commandBus->execute(new UpdateRoleCommand($formData, $id));

        return $this->respondOK($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $response=$this->commandBus->execute(new DeleteRoleCommand($id));
        return $this->respondOK($response);
    }

    public function getGroupRoles($groupId)
    {
        $response=$this->commandBus->execute(new SearchRolesByGroupCommand($groupId));
        return $this->respondWithCollection($response, new RoleTransformer, 'Role');
    }

    public function attachUsersToRoles(Request $request, $roleId)
    {
        $formData=$request->all();
        if (isset($formData['users']) && is_array($formData['users']) && count($formData['users']) > 0 && $roleId != "") {
            $this->roleuserRepo->addUsersToRole($formData['users'], $roleId);
            /** 
             * For updating user about with its new permission
             */
            $job = (new UserLogoutIntimationJob($formData['users']));
            $this->dispatch($job);

            return $this->getUsersByRole($roleId);
        } else {
            throw new SeException('Invalid Input users or roles', 422, 7210401);
        }
    }

    public function getUsersByRole($role)
    {
        $response = $this->roleuserRepo->getUsersByRoles($role);
        return $this->respondWithCollection($response, new UserTransformer, 'User');
    }

    public function removeUsersByRole(Request $request, $role)
    {
        $formData=$request->all();
        if (isset($formData['users']) && is_array($formData['users']) && count($formData['users']) > 0 && $role != "") {
            $this->roleuserRepo->deleteUsersByRole($formData['users'], $role);
            $job = (new UserLogoutIntimationJob($formData['users']));
            $this->dispatch($job);
            return $this->respondOk('Users assigned to role deleted successfully');
        } else {
            throw new SeException('Invalid Input users or roles', 422, 7210401);
        }
    }

    public function getUnassignedUsersByRole($role){
        $response = $this->roleuserRepo->getUnassignedUsers($role);
        return $this->respondWithCollection($response, new UserTransformer, 'User');
    }
}
