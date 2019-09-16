<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class ExportPOMRevisionsToTechpackCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    private $techpack;

    /**
     * @var SampleContainerRepository
     */
    private $sampleContainer;

    /**
     * @var SampleRepository
     */
    private $sample;


    /**
     * @param TechpackRepository $techpack
     * @param SampleRepository   $sample
     */
	public function __construct(
        TechpackRepository $techpack,
        SampleContainerRepository $sampleContainer,
        SampleRepository $sample
    ) {
        $this->techpack = $techpack;
        $this->sampleContainer = $sampleContainer;
        $this->sample = $sample;
	}

	public function handle($command)
	{
        $sample = $this->sample->getBySampleContainerIdAndSampleId(
            $command->sampleContainerId,
            $command->sampleId
        );

        $techpack = $this->sampleContainer
                         ->find($command->sampleContainerId)
                         ->techpack()
                         ->first();

        $techpackSamples = (array) $techpack->poms->samples;
        $techpackPOM = (array) $techpack->poms;

        if (!$this->doesSampleExist($techpackSamples, $command->sampleId)) {
            $updatedPOM = $this->addNewSampleToTechpackPOM(
                $techpackPOM,
                $sample
            );
            $techpack->poms = $updatedPOM;
            $techpack->save();
        } else {
            $updatedPOM = $this->updateSampleToTechpackPOM(
                $techpackPOM,
                $sample
            );
            $techpack->poms = $updatedPOM;
            $techpack->save();
        }

        return true;
	}

    /**
     * Checks if the sample is already there in the techpack or not
     * @param  array $techpackSamples
     * @param  string $sampleId
     * @return boolean
     */
    private function doesSampleExist($techpackSamples, $sampleId)
    {
        foreach ($techpackSamples as $techpackSample) {
            if ($techpackSample->id == $sampleId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Export a new sample revisions to techpack
     * @param object $techpackPOM
     * @param object $sample
     */
    private function addNewSampleToTechpackPOM($techpackPOM, $sample)
    {
        $sampleId = $sample->id;
        $newSample = [
            'id' => $sampleId,
            'name' => $sample->title,
        ];
        array_push($techpackPOM['samples'], (object) $newSample);

        foreach($sample->pom->pom as $samplePOM) {
            foreach ($techpackPOM['pom'] as $pom) {
                if ($samplePOM->pomId == $pom->pomId) {
                    $value = [
                        "sampleId" => $sampleId,
                        "value" => $samplePOM->revisions,
                        "invalid" =>false,
                    ];
                    array_push($pom->values, (object) $value);
                }
            }
        }
        return $techpackPOM;
    }

    /**
     * Update new sample revisions to techpack
     * @param object $techpackPOM
     * @param object $sample
     */
    private function updateSampleToTechpackPOM($techpackPOM, $sample)
    {
        $sampleId = $sample->id;

        foreach ($techpackPOM['samples'] as $techpackSample) {
            if ($techpackSample->id == $sampleId) {
                $techpackSample->name = $sample->title;
            }
        }

        foreach($sample->pom->pom as $samplePOM) {
            foreach ($techpackPOM['pom'] as $pom) {
                if ($samplePOM->pomId == $pom->pomId) {
                    foreach ($pom->values as $value) {
                        if ($value->sampleId == $sampleId) {
                            $value->value = $samplePOM->revisions;
                        }
                    }
                }
            }
        }
        return $techpackPOM;
    }

}