<?php
namespace Platform\Observers\Handlers\Commands;

use Platform\Observers\Repositories\TNAActivityRepository;

class GetTNAActivityDetailsCommandHandler{

	 /**
     * @var taskActivityRepository
     */
    private $tnaActivityRepository;

    public function __construct(TNAActivityRepository $tnaActivityRepository)
    {
        $this->tnaActivityRepository = $tnaActivityRepository;
    }
    
    /**
     * @param  StoreNewRuleRequestCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data =$this->tnaActivityRepository->getTNADetails($command);
        return $data;
    }
}