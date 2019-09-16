<?php
namespace Platform\Observers\Handlers\Commands;

use Platform\Observers\Repositories\SampleActivityRepository;

class GetSampleActivityDetailsCommandHandler{

	 /**
     * @var sampleActivityRepository
     */
    private $sampleActivityRepository;

    public function __construct(SampleActivityRepository $sampleActivityRepository)
    {
        $this->sampleActivityRepository = $sampleActivityRepository;
    }
    
    /**
     * @param  StoreNewRuleRequestCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data =$this->sampleActivityRepository->getSampleDetails($command);
        return $data;
    }
}