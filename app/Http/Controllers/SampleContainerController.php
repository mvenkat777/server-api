<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddNewSampleContainerCommand;
use Platform\SampleContainer\Commands\GetSampleContainerByIdCommand;
use Platform\SampleContainer\Commands\ListSampleContainersCommand;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\SampleContainer\Transformers\MetaSampleContainerTransformer;
use Platform\SampleContainer\Transformers\SampleContainerTransformer;
use Platform\SampleContainer\Validators\SampleContainerValidator;

class SampleContainerController extends ApiController
{
    /**
     * Command Bus
     *
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    private $commandBus;

    /**
     * The Sample Container Validator
     * @var SampleContainerValidator
     */
    private $validator;

    /**
     * Construct method
     *
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(
        DefaultCommandBus $commandBus,
        SampleContainerValidator $validator,
        SampleContainerRepository $containerRepo
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->containerRepo = $containerRepo;

        parent::__construct(new Manager());
    }

    /**
     * Get sample list
     * @return mixed
     */
    public function index(Request $request)
    {
        $sampleContainers = $this->commandBus->execute(
            new ListSampleContainersCommand($request->all())
        );
     // dd($sampleContainers);
        if($sampleContainers->count() != 0) {
            return $this->respondWithPaginatedCollection(
                $sampleContainers,
                new SampleContainerTransformer,
                'sampleContainer'
            );
        }
        return $this->respondOk('There are no samples yet.');
    }

    /**
     * Add a new Sample Container
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validator->setCreationRules()
                        ->validate($request->all());

        $sampleContainer = $this->commandBus->execute(
            new AddNewSampleContainerCommand($request->all())
        );

        if ($sampleContainer) {
            return $this->respondWithNewItem(
                $sampleContainer,
                new SampleContainerTransformer,
                'SampleContainer'
            );
        }
        return $this->respondWithError("Failed to add a new Sample Container.", 500);
    }

    /**
     * Get a sample container by Id
     * @return mixed
     */
    public function find($sampleContainerId)
    {
        $sampleContainer = $this->commandBus->execute(
            new GetSampleContainerByIdCommand($sampleContainerId)
        );

        if($sampleContainer) {
            return $this->respondWithItem(
                $sampleContainer,
                new SampleContainerTransformer,
                'sampleContainer'
            );
        }
        return $this->respondWithError('Sorry, we messed up.', 500);
    }

    /**
     * Get the pom data for the techpack
     * @return mixed
     */
    public function getTechpackPOM($sampleContainerId, $sampleId)
    {
        $techpackId = \App\SampleContainer::find($sampleContainerId)->techpack()->first()->id;
        $poms = \App\Techpack::find($techpackId)->poms;

        if (!isset($poms->pom) && empty($poms->pom)) {
            return $this->respondWithArray(['data' => $poms]);
        }

        unset($poms->sizeRange);
        foreach ($poms->pom as $pom) {
            $pom->requested = '';
            $pom->actual = '';
            $pom->deviation = '';
            $pom->comment = '';
            $pom->revisions = '';
            foreach ($pom->values as $value) {
                if ($value->sampleId == $sampleId) {
                    $pom->requested = $value->value;
                }
            }
            unset($pom->values);
        }

        return $this->respondWithArray(['data' => ['pom' => $poms->pom]]);
    }
    
    /**
     * Complete Sample Containe
     * @param  string $id
     * @return string     
     */
    public function completeSampleContainer($id)
    {
        $complete = $this->containerRepo->completeSampleContainer($id);
        if ($complete) {
            return $this->respondOk('Successfully Completed the Container');
        }
        return $this->respondWithError('Failed to complete');
    }

    /**
     * Undo Sample Container
     * @param  string $id 
     * @return string     
     */
    public function undoSampleContainer($id)
    {
        $undo = $this->containerRepo->undoSampleContainer($id);
        if ($undo) {
            return $this->respondOk('Successfully undo the Container');
        }
        return $this->respondWithError('Failed to undo');
    }
    
    /**
     * Archives a style
     *
     * @param Request $request
     * @param string $containerId
     * @return mixed
     */
    public function destroy(Request $request, $containerId) 
    {
        $data = $request->all();
        if (isset($data['type']) && $data['type'] == 'delete') {
            $archived = $this->containerRepo->deleteContainer($containerId);
            if ($archived) {
                    return $this->respondOk("Successfully deleted the Sample Container");
            } else {
                    return $this->respondWithError("Failed to archive the Sample Container. Please try again.");
            }
        } else {
            $archived = $this->containerRepo->archiveContainer($containerId);
            if ($archived) {
                    return $this->respondOk("Successfully archived the Sample Container");
            } else {
                    return $this->respondWithError("Failed to archive the Sample Container. Please try again.");
            }
        }
    }   

    /**
     * rollback sample container
     * @param  string $containerId  
     * @return string          
     */
    public function rollbackSampleContainer($containerId)
    {
        $rollback = $this->containerRepo->rollbackContainer($containerId);
        if ($rollback) {
                return $this->respondOk("Successfully rollbacked the Sample Container");
        } else {
                return $this->respondWithError("Failed to rollback the Sample Container. Please try again.");
        }
    }
}
