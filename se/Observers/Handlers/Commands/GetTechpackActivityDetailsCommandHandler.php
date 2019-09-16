<?php
namespace Platform\Observers\Handlers\Commands;

use Platform\Observers\Repositories\TechpackActivityRepository;

class GetTechpackActivityDetailsCommandHandler{

	 /**
     * @var techpackActivityRepository
     */
    private $techpackActivityRepository;

    public function __construct(TechpackActivityRepository $techpackActivityRepository)
    {
        $this->techpackActivityRepository = $techpackActivityRepository;
    }
    
    /**
     * @param  StoreNewRuleRequestCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data =$this->techpackActivityRepository->getTechpackDetails($command);
        return $data;
    }
}