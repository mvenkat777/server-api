<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddNewSampleCommand;
use Platform\SampleContainer\Commands\DeleteSampleByIdCommand;
use Platform\SampleContainer\Commands\ExportPOMRevisionsToTechpackCommand;
use Platform\SampleContainer\Commands\GetSampleByIdCommand;
use Platform\SampleContainer\Commands\SampleExportCommand;
use Platform\SampleContainer\Commands\UpdateSampleCommand;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;
use Platform\SampleContainer\Transformers\SampleTransformer;
use Platform\SampleContainer\Validators\SampleValidator;

class SampleController extends ApiController
{
    /**
     * The command bus
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * The validator
     * @var SampleValidator
     */
    private $validator;

    /**
     * Constructing...
     *
     * @param DefaultCommandBus $commandBus
     */
    function __construct(
        DefaultCommandBus $commandBus,
        SampleValidator $validator,
        SampleRepository $sampleRepo
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->sampleRepo = $sampleRepo;

        parent::__construct(new Manager());
    }

    /**
     * Create the sample
     * @param  string  $sampleContainerId
     * @param  Request $request
     * @return mixed
     */
    public function store($sampleContainerId, Request $request)
    {
        $data = $request->all();
        $data['sampleContainerId'] = $sampleContainerId;
        $this->validator->setCreationRules()->validate($data);

        $sample = $this->commandBus->execute(
            new AddNewSampleCommand($data)
        );
        if ($sample) {
            return $this->respondWithNewItem(
                $sample,
                new SampleTransformer,
                'Sample'
            );
        }
        return $this->respondWithError("Sorry, we messed up.", 500);
    }

    /**
     * Update a sample entry
     * @param  string  $sampleContainerId
     * @param  Request $request
     * @return mixed
     */
    public function update($sampleContainerId, $sampleId, Request $request)
    {
        $data = $request->all();
        $data['sampleContainerId'] = $sampleContainerId;
        $data['sampleId'] = $sampleId;
        $this->validator->setUpdationRules()->validate($data);

        $sample = $this->commandBus->execute(
            new UpdateSampleCommand($data)
        );
        if ($sample) {
            return $this->respondWithItem(
                $sample,
                new SampleTransformer,
                'Sample'
            );
        }
        return $this->respondWithError("Sorry, we messed up.", 500);
    }

    /**
     * Find the sample
     * @param  string $sampleContainerId
     * @param  string $sampleId
     * @return mixed
     */
    public function find($sampleContainerId, $sampleId)
    {
        $sample = $this->commandBus->execute(
            new GetSampleByIdCommand($sampleContainerId, $sampleId)
        );

        if($sample) {
            return $this->respondWithItem($sample, new SampleTransformer, 'sample');
        }
        return $this->respondWithError('Sorry, we messed up.', 500);
    }

    /**
     * Comple Style
     * @param  string $containerId
     * @param  string $sampleId
     * @return string
     */
    public function completeSample($containerId, $sampleId)
    {
        $completed = $this->sampleRepo->completeSample($containerId, $sampleId);
        if ($completed) {
            return $this->respondOk('Successfully completed the sample');
        }
        return $this->respondWithError('Failed to complete');
    }

    /**
     * undo sample
     * @param  string $containerId
     * @param  string $sampleId
     * @return string
     */
    public function undoStyle($containerId, $sampleId)
    {
        $undo = $this->sampleRepo->undoSample($containerId, $sampleId);
        if ($undo) {
            return $this->respondOk('Successfully undo the sample');
        }
        return $this->respondWithError('Failed to undo');
    }

    /**
     * Delete the sample
     * @param  string $sampleContainerId
     * @param  string $sampleId
     * @return mixed
     */
    public function destroy(Request $request, $sampleContainerId, $sampleId)
    {
        $data = $request->all();
        if (isset($data['type']) && $data['type'] == 'delete') {
            $deleted = $this->commandBus->execute(
                new DeleteSampleByIdCommand($sampleContainerId, $sampleId)
            );

            if($deleted) {
                return $this->respondOk("Sample deleted successfully.");
            }
            return $this->respondWithError('Sorry, we messed up.', 500);
        } else {
            $archived = $this->sampleRepo->archiveSample($sampleId);
            if ($archived) {
                    return $this->respondOk("Successfully archived the Sample ");
            } else {
                    return $this->respondWithError("Failed to archive the Sample . Please try again.");
            }
        }
    }

    /**
     * Rollback Ssample
     * @param  string $containerId
     * @param  string $sampleId
     * @return satring
     */
    public function rollbackSample($containerId, $sampleId)
    {
        $rollback = $this->sampleRepo->rollbackSample($sampleId);
        if ($rollback) {
                return $this->respondOk("Successfully rollbacked the Sample ");
        } else {
                return $this->respondWithError("Failed to rollback the Sample . Please try again.");
        }
    }

    /**
     * Export the POM from samples to techpack
     * @param  string $sampleContainerId
     * @param  string $sampleId
     * @return mixed
     */
    public function exportPOM($sampleContainerId, $sampleId)
    {
        $exported = $this->commandBus->execute(
            new ExportPOMRevisionsToTechpackCommand($sampleContainerId, $sampleId)
        );

        if($exported) {
            return $this->respondOk("POM revisions exported to techpack.");
        }
        return $this->respondWithError('Sorry, we messed up.', 500);
    }

    /**
     * Export the sample as a pdf file
     * @param  string $sampleContainerId
     * @param  string $sampleId
     * @return mixed
     */
    public function export($sampleContainerId, $sampleId, Request $request)
    {
        $exported = $this->commandBus->execute(
            new SampleExportCommand($sampleContainerId, $sampleId, $request->all())
        );

        if ($exported) {
            $data['data'] = [
                'downloadLink' => $exported,
            ];
            return $this->respondWithArray($data);
        }
        return $this->respondWithError("Sample export failed. Please try again.");
    }
}
