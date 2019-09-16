<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class UpdateSamplePOMFieldCommandHandler implements CommandHandler
{
    /**
     * @var TechpackRepository
     */
    private $techpackRepository;

    /**
     * @var SampleRepository
     */
    private $sampleRepository;

    /**
     * @var SampleContainerRepository
     */
    private $sampleContainerRepository;

    /**
     * @param TechpackRepository $techpackRepository
     * @param SampleRepository $sampleRepository
     * @param SampleContainerRepository $sampleContainerRepository
     */

    /**
     * @var [type]
     */
    public function __construct(
        TechpackRepository $techpackRepository,
        SampleRepository $sampleRepository,
        SampleContainerRepository $sampleContainerRepository
    ) {
        $this->techpackRepository = $techpackRepository;
        $this->sampleRepository = $sampleRepository;

        /**
         * @var [type]
         */
        $this->sampleContainerRepository = $sampleContainerRepository;
    }

    /**
     * @param  UpdateSamplePOMFieldCommand $command
     * @return null
     */
	public function handle($command)
	{
        $techpack = $command->techpack;
        $techpackPOM = $techpack->poms;
        $sampleContainer = $this->sampleContainerRepository->getByTechpackId($techpack->id);
        if ($sampleContainer) {
            $samples = $sampleContainer->samples()->get();
            foreach ($samples as $sample) {
                $samplePOM = $sample->pom;
                $sample->pom = $this->getDiff($techpackPOM, $samplePOM);
                $sample->update();
            }
        }

	}

    /**
     * Gets the difference between techpackPOM and samplePOM
     * @param  array $techpackPOM
     * @param  array $samplePOM
     * @return mixed
     */
    public function getDiff($techpackPOM, $samplePOM)
    {
        if ($samplePOM == null) {
            return $techpackPOM;
        }

        $updatedPOM['samples'] = [];
        $updatedPOM['pom'] = [];

        $samplePOM = json_decode(
            json_encode($samplePOM),
            true
        );

        $sampleIds = collect($samplePOM['samples'])->lists('id')->toArray();
        foreach ($techpackPOM->samples as $techpackSample) {
            array_push($updatedPOM['samples'], (array) $techpackSample);
        }

        $pomIds = collect($samplePOM['pom'])->lists('pomId')->toArray();

        foreach ($techpackPOM->pom as $pom) {
            if (!in_array($pom->pomId, $pomIds)) {
                unset($pom->values);
                $pom->requested = '';
                $pom->actual = '';
                $pom->deviation = '';
                $pom->comment = '';
                $pom->revisions = '';
                array_push($updatedPOM['pom'], (array) $pom);
            } else {
                $existingPOM = collect($samplePOM['pom'])->where('pomId', $pom->pomId)->first();
                array_push($updatedPOM['pom'], $existingPOM);
            }
        }
        return $updatedPOM;
    }

}
