<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\TechpackUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Observers\Commands\GetTechpackActivityDetailsCommand;
use Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer;
use Platform\TNA\Transformers\MetaTNATransformer;
use Platform\Techpacks\Commands\AssociateTechpackCommand;
use Platform\Techpacks\Commands\CloneTechpackCommand;
use Platform\Techpacks\Commands\DeleteTechpackCommand;
use Platform\Techpacks\Commands\ForceDeleteTechpackCommand;
use Platform\Techpacks\Commands\GenerateTechpackSchemaCommand;
use Platform\Techpacks\Commands\GetTechpackByIdCommand;
use Platform\Techpacks\Commands\GetTechpackMetaCommand;
use Platform\Techpacks\Commands\GetTechpackSchemaCommand;
use Platform\Techpacks\Commands\ListTechpacksCommand;
use Platform\Techpacks\Commands\RegisterNewTechpackCommand;
use Platform\Techpacks\Commands\RestoreTechpackCommand;
use Platform\Techpacks\Commands\SearchTechpackCommand;
use Platform\Techpacks\Commands\ShareTechpackCommand;
use Platform\Techpacks\Commands\UpdateTechpackCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Techpacks\Repositories\Eloquent\EloquentTechpackRepository;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;
use Platform\Techpacks\Transformers\TechpackListTransformer;
use Platform\Techpacks\Transformers\TechpackTransformer;
use Platform\Users\Transformers\UserTransformer;
use Platform\Techpacks\Commands\RollbackTechpackCommand;
use Platform\Techpacks\Commands\ArchiveTechpackCommand;

/**
 * Class TechpackController
 * @package App\Http\Controllers
 */
class TechpackController extends ApiController
{
    /**
     * @var DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus, TechpackRepository $techpack)
    {
        $this->commandBus = $commandBus;
        $this->techpack = $techpack;
        $this->manager = new Manager();

        parent::__construct(new Manager());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('search') == 'share' || $request->get('search') == 'public') {
            $search = $request->get('search');
            $techpack = $this->commandBus->execute(new SearchTechpackCommand(
                $search,
                $request->get('item') ? $request->get('item') : 100
            ));
            if ($techpack) {
                return $this->respondWithPaginatedCollection($techpack, new TechpackTransformer, 'users');
            } else {
                return $this->respondInternalError();
            }
        } elseif ($request->get('search')) {
            $onlyTrashed = $request->get('onlyTrashed') ? $request->get('onlyTrashed') : false;
            $withTrashed = $request->get('withTrashed') ? $request->get('withTrashed') : false;
            $withScope = $request->get('withScope') ? $request->get('withScope') : 'owned';
            $withCollection = $request->get('withCollection') ? $request->get('withCollection') : 'all';
            $app = $request->get('app') ? $request->get('app') : 'platform';

            try {
                $techpacks = $this->commandBus->execute(
                    new ListTechpacksCommand(
                        (boolean)$onlyTrashed,
                        (boolean)$withTrashed,
                        $withScope,
                        $withCollection,
                        $app,
                        $request->get('item') ? $request->get('item') : 100
                    )
                );

                return $this->respondWithPaginatedCollection(
                    $techpacks,
                    new TechpackTransformer,
                    'techpacks'
                );
            } catch (\Exception $e) {
                return $this->respondError($e);

                return $this->respondInternalError('We messed up!', "20101");
            }
        }
    }

    /**
     * get techpack Activity Log
     *
     * @param  Request $request
     * @return mixed
     */
    public function getTechpackActivity($taskId){
        $techpack = $this->commandBus->execute(new GetTechpackActivityDetailsCommand($taskId));
        return $this->respondWithArray(['data' => $techpack]);
    }

    /**
     * Get only techpack metas
     * @return mixed
     */
    public function getMeta(Request $request)
    {
        $app = isset($request->app) ? $request->app : null;
        $techpackMeta = $this->commandBus->execute(
            new GetTechpackMetaCommand($app)
        );

        if ($techpackMeta) {
            return $this->respondWithCollection($techpackMeta, new MetaTechpackTransformer, 'techpackMeta');
        }

        return $this->setStatusCode(404)
                    ->respondError("No techpacks found.");
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            if (!$this->isValid($request->all())) {
                return $this->respondBadRequest(
                    "The inputs can't be validated. The techpack can't be saved.",
                    "40001"
                );
            }
        } catch (\Exception $e) {
            return $this->respondInternalError("We messed up!", "50001");
        }
        try {
            $techpack = $this->commandBus->execute(
                new RegisterNewTechpackCommand($request->all())
            );

            return $this->respondWithNewItem($techpack, new TechpackTransformer, 'techpack');
        } catch (\Exception $e) {
            return $this->respondError($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $withTrashed = $request->get('withTrashed') ? $request->get('withTrashed') : false;
        $getFields = $request->get('get') ? $request->get('get') : false;

        if ($getFields) {
            $getFields = explode(',', $getFields);
        }

        try {
            if (!$this->isValidUUID(['techpack_id' => $id])) {
                return $this->respondBadRequest("We couldn't identify the techpack identity format.", "40001");
            }
        } catch (\Exception $e) {
            return $this->respondInternalError("We messed up!", "50001");
        }
        try {
            $techpack = $this->commandBus->execute(
                new GetTechpackByIdCommand($id, (boolean)$withTrashed, $getFields)
            );

            return $this->respondWithItem($techpack, new TechpackTransformer, 'techpack');
        } catch (\Exception $e) {
            return $this->respondError($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            if (!$this->isValidUUID(['techpack_id' => $id])) {
                return $this->respondBadRequest(
                    "We couldn't identify the techpack identity format.",
                    "40001"
                );
            }
        } catch (\Exception $e) {
            return $this->respondInternalError("We messed up!", "50001");
        }

        // try {
            $techpack = $this->commandBus->execute(new UpdateTechpackCommand($request->all(), $id));

            return $this->respondWithItem($techpack, new TechpackTransformer, 'techpack');
        // } catch (\Exception $e) {
        //     return $this->respondError($e);
        // }
    }

    /**
     * complete a techpack
     * @param  string  $id 
     * @return string     
     */
    public function completeTechpack($id)
    {
        $complete = $this->techpack->completeTechpack($id);
        if ($complete) {
            return $this->respondOk('Techpack marked as completed successfully');
        }
        return $this->respondWithError('Failed to complete techpack');
    }

    /**
     * Undo techpack
     * @param  string $id \
     * @return string     
     */
    public function undoTechpack($id)
    {
        $undo = $this->techpack->undoTechpack($id);
        if ($undo) {
            return $this->respondOk('Techpack marked as undo successfully');
        }
        return $this->respondWithError('Failed to undo techpack');
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $techpackUser = TechpackUser::where('user_id', $userId)->where('techpack_id', $id)->first();

        if ($techpackUser) {
            if ($techpackUser->permission != 'owner') {
                return $this->respondUnauthorizedError('You need to be the owner of the techpack to perform this action.');
            }
        }

        //try {
            if ($request->get('type') == 'archive') {
                $isTechpackArchived = $this->commandBus->execute(
                    new ArchiveTechpackCommand($id)
                );
                if ($isTechpackArchived) {
                    return $this->respondOk(
                        'The techpack has been archived.',
                        "20001"
                    );
                }
            } else {
                $isTechpackDeleted = $this->commandBus->execute(
                    new DeleteTechpackCommand($id)
                );
                if ($isTechpackDeleted) {
                    return $this->respondOk(
                        'The techpack has been deleted.',
                        "20001"
                    );
                }
            }
        //} catch (\Exception $e) {
         //   return $this->respondError($e);
        //}

        return $this->respondInternalError("We messed up!", "50001");
    }


    public function getSchema(Request $request)
    {
        $version = $request->get('version') ? $request->get('version') : 0;
        try {
            $techpack = $this->commandBus->execute(
                new GetTechpackSchemaCommand($version)
            );

            return $techpack;
        } catch (\Exception $e) {
            return $this->respondError($e);

            return $this->respondInternalError('We messed up!', "20101");
        }


        return ($techpack);
    }


    public function generateSchema()
    {
        try {
            $techpack = $this->commandBus->execute(new GenerateTechpackSchemaCommand());

            return $techpack;
        } catch (\Exception $e) {
            return $this->respondError($e);

            return $this->respondInternalError('We messed up!', "20101");
        }
    }


    public function associations(Request $request, $id)
    {
        $action = $request->get('action') ? $request->get('action') : 'attach';
        if (!$this->isValid(
            [
                    'id' => $id,
                    'action' => $action,
                    'user_id' => $request->get('user_id'),
                    'permission' => $request->get('permission')
                ],
            [
                    'id' => 'exists:techpacks,id',
                    'action' => 'in:attach,detach',
                    'user_id' => 'exists:users,id',
                    'permission' => 'in:can_read,can_edit'
                ]
        )
        ) {
            return $this->respondBadRequest("The inputs can't be validated. Associations not updated.", "40001");
        }
        try {
            $associations = $this->dispatchFrom(
                AssociateTechpack::class,
                $request,
                ['id' => $id, 'action' => $action, 'permission' => $request->get('permission')]
            );

            return $this->respondWithCollection($associations, new UserTransformer, 'users');
        } catch (\Exception $e) {
            return $this->respondError($e);

            return $this->respondInternalError('We messed up!', "20101");
        }
    }

    /**
     * Sahre a techpack with other users
     * @param  Request $request
     * @param  string  $techpackId
     * @return mixed
     */
    public function share(Request $request, $techpackId)
    {
        $techpack = $this->commandBus->execute(new ShareTechpackCommand($techpackId, $request->all()));

        if ($techpack) {
            return $this->respondWithItem($techpack, new TechpackTransformer, 'users');
        } else {
            return $this->respondInternalError();
        }
    }

    /**
     * Clone another techpack into logged in user's account
     * @param  Request $request
     * @param  string  $techpackId
     * @return mixed
     */
    public function cloneTechpack(Request $request, $techpackId)
    {
        $techpack = $this->commandBus->execute(new CloneTechpackCommand($techpackId, $request->all()));

        if ($techpack) {
            return $this->respondWithItem($techpack, new TechpackTransformer, 'techpack');
        } else {
            return $this->respondNotModified("Not able to clone the techpack. Pleade try again.");
        }
    }

    /**
     * Filter the techpack list response
     * @param  Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        $techpacks = $this->techpack->filterTechpack($request->all());

        return $this->respondWithPaginatedCollection(
            $techpacks,
            new TechpackListTransformer,
            'techpacks'
        );
    }

    /**
     * @param  string $techpackId
     * @return mixed
     */
    public function getTechpackSample($techpackId)
    {
        $techpacks = $this->commandBus->execute(new GetTechpackByIdCommand($techpackId));
        return $this->respondWithCollection($techpacks->sample, new MetaSampleSubmissionTransformer, 'sample');
    }

    /**
     * @param  string $techpackId
     * @return mixed
     */
    public function getTechpackTNA($techpackId)
    {
        $techpacks = $this->commandBus->execute(new GetTechpackByIdCommand($techpackId));
        return $this->respondWithCollection($techpacks->TNA, new MetaTNATransformer, 'tna');
    }

    /**
     * @param  string $techpackId
     * @return mixed
     */
    public function getTechpackRelatedData($techpackId)
    {
        $techpacks = $this->commandBus->execute(new GetTechpackByIdCommand($techpackId));

        $tna = new Collection($techpacks->TNA, new MetaTNATransformer);
        $data['tna'] = $this->manager->createData($tna)->toArray()['data'];

        $sample = new Collection($techpacks->sample, new MetaSampleSubmissionTransformer);
        $data['sample'] = $this->manager->createData($sample)->toArray()['data'];

        return $this->respondWithArray(['data' => $data]);
    }

    /**
     * Rollback the archived techpack.
     *
     * @param  string  $id
     * @return mixed
     */
    public function rollback($id)
    {
        $result = $this->commandBus->execute(new RollbackTechpackCommand($id));

        if($result) {
            return $this->respondOk('Rollback is successful');
        }
        else {
            return $this->setStatusCode(500)
                ->respondWithError('Failed to rollback the techpack. Please try again.', 50000);
        }
    }

    /**
     * Lock Techpack
     * @param  string $techpackId 
     * @return string             
     */
    public function lock($techpackId)
    {
        $result = $this->techpack->lockTechpack($techpackId);

        if($result) {
            return $this->respondOk('Techpack locked successful');
        }
        else {
            return $this->setStatusCode(500)
                ->respondWithError('Failed to lock the techpack. Please try again.', 50000);
        }
    }

    /**
     * Unlock Techpack
     * @param  string $techpackId 
     * @return string             
     */
    public function unlock(Request $request, $techpackId)
    {
        $data = $request->all();
        $result = $this->techpack->unlockTechpack($data['code'], $techpackId);

        if($result) {
            return $this->respondOk('Techpack unlocked successful');
        }
        else {
            return $this->setStatusCode(500)
                ->respondWithError('Failed to unlock the techpack. Please try again.', 50000);
        }
    }
}
