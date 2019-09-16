<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddNewSampleCriteriaCommand;
use Platform\SampleContainer\Commands\DeleteSampleCriteriaByIdCommand;
use Platform\SampleContainer\Commands\GetSampleCriteriaByIdCommand;
use Platform\SampleContainer\Commands\UpdateSampleCriteriaCommand;
use Platform\SampleContainer\Transformers\SampleCriteriaTransformer;
use Platform\SampleContainer\Validators\SampleCriteriaValidator;

class SampleCriteriaController extends ApiController
{
    /**
     * The sampleCriteria validator
     * @var SampleCriteriaValidator
     */
    private $validator;

    /**
     * The commandbus
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * Construct the necessaary s tuff for sample criteria act.
     * @param SampleCriteriaValidator $validator
     * @param DefaultCommandBus       $commandBus
     */
    public function __construct(SampleCriteriaValidator $validator, DefaultCommandBus $commandBus)
    {
        $this->validator = $validator;
        $this->commandBus = $commandBus;

        parent::__construct(new Manager());
    }

    /**
     * Add a criteria to a sample
     * @param  string  $sampleId
     * @param  Request $request
     * @return mixed
     */
    public function store($sampleId, Request $request)
    {
        $data = $request->all();
        $data['sampleId'] = $sampleId;

        $this->validator->setCreationRules()->validate($data);
        $sampleCriteria = $this->commandBus->execute(
            new AddNewSampleCriteriaCommand($data)
        );

        if ($sampleCriteria) {
            return $this->respondWithNewItem(
                $sampleCriteria,
                new SampleCriteriaTransformer
            );
        }
        return $this->respondWithError("Failed to add the criteria. Please try again.");
    }

    /**
     * Get a sample criteria by its id
     * @param  string $sampleId
     * @param  string $criteriaId
     * @return mixed
     */
    public function find($sampleId, $criteriaId)
    {
        $sampleCriteria = $this->commandBus->execute(
            new GetSampleCriteriaByIdCommand($sampleId, $criteriaId)
        );

        if ($sampleCriteria) {
            return $this->respondWithItem(
                $sampleCriteria,
                new SampleCriteriaTransformer
            );
        }
        return $this->respondWithError("Sample criteria not found.");
    }

    /**
     * Update a sample criteria
     * @param  string  $sampleId
     * @param  string  $criteriaId
     * @param  Request $request
     * @return mixed
     */
    public function update($sampleId, $criteriaId, Request $request)
    {
        $data = $request->all();
        $data['sampleId'] = $sampleId;
        $data['criteriaId'] = $criteriaId;

        $this->validator->setUpdationRules()->validate($data);
        $sampleCriteria = $this->commandBus->execute(
            new UpdateSampleCriteriaCommand($data)
        );

        if ($sampleCriteria) {
            return $this->respondWithItem(
                $sampleCriteria,
                new SampleCriteriaTransformer
            );
        }
        return $this->respondWithError("Failed to update the criteria. Please try again.");
    }

    /**
     * Delete a sample criteria
     * @param  string $sampleId
     * @param  string $criteriaId
     * @return string
     */
    public function destroy($sampleId, $criteriaId)
    {
        $deleted = $this->commandBus->execute(
            new DeleteSampleCriteriaByIdCommand($sampleId, $criteriaId)
        );

        if ($deleted) {
            return $this->respondOk("Sample criteria deleted successfully.");
        }
        return $this->respondWithError("Sorry, we messed up.");
    }
}
