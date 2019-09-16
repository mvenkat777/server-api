<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class AddNewSampleContainerCommandHandler implements CommandHandler
{
    /**
     * The sample container repository
     * @var SampleContainerRepository
     */
    private $sampleContainer;

    /**
     * The techpack repository
     * @var TechpackRepository
     */
    private $techpack;

    /**
     * @param SampleContainerRepository $sampleContainer
     * @param TechpackRepository $techpack
     */
	public function __construct(
        SampleContainerRepository $sampleContainer,
        TechpackRepository $techpack
    ) {
        $this->sampleContainer = $sampleContainer;
        $this->techpack = $techpack;
	}

    /**
     * Handle the AddNewSampleContainerCommand
     * @param  AddNewSampleContainerCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        $techpackId = $command->techpackId;

        if ($this->haveASampleContainer($techpackId)) {
            throw new SeException("This Techpack is already having a Sample Container.", 409);
        }
        $data = $this->getDetailsFromTechpack($techpackId);
        return $this->sampleContainer->addSampleContainer($data);
	}

    /**
     * Check if the Techpack is already having a sample Container
     * @param  string $techpackId
     * @return boolean
     */
    private function haveASampleContainer($techpackId)
    {
        return is_null($this->sampleContainer->getByTechpackId($techpackId)) ? false : true;
    }

    /**
     * Get the details required for sample container generation from techpack
     * @param  string $techpackId
     * @return mixed
     */
    private function getDetailsFromTechpack($techpackId)
    {
        $techpack = $this->techpack->find($techpackId);

        if ($techpack) {
            return [
                'techpackId' => $techpackId,
                'flatImage' => $techpack->image,
                'customerId' => $techpack->customer_id,
                'styleCode' => $techpack->style_code,
            ];
        }

        throw new SeException('Not able to find the techpack. Please try again.', 404);
    }

}