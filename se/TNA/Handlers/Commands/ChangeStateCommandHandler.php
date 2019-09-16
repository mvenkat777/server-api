<?php

namespace Platform\TNA\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Validators\TNACommandValidator;
use Platform\TNA\Helpers\TNAPublisher;

class ChangeStateCommandHandler extends TNACommandValidator implements CommandHandler 
{
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

	/**
	 * @var Platform\TNA\Helpers\TNAPublisher
	 */
	protected $tnaPublisher;

	/**
	 * @param DefaultCommandBus $commandBus    
	 * @param TNARepository     $tnaRepository 
     * @param TNAPublisher      $tnaPublisher
	 */
	public function __construct(DefaultCommandBus $commandBus,
								TNARepository $tnaRepository,
                                TNAPublisher $tnaPublisher
                                )
	{
		$this->commandBus = $commandBus;
		$this->tnaRepository = $tnaRepository;
        $this->tnaPublisher = $tnaPublisher;
	}

	/**
	 * @param  ChangeStateCommand $command 
	 * @return TNA          
	 */
	public function handle($command)
	{
        /*
		if(!$this->validateChangeStatus($command)){
			throw new SeException("Error Processing Request", 422, 4200123);
		}
         */
		$command->tnaState = TNAHelper::convertToDbState($command->tnaState);
		\DB::beginTransaction();
		$tna = $this->tnaRepository->changeState((array)$command);
		
        //\Queue::push($this->tnaPublisher->publish($tna));
        //$tna = $this->tnaPublisher->publish($tna);
		//$this->doActionForState($command->tnaState, $tna);
		\DB::commit();
        return $tna;
		//return $this->tnaRepository->getById($tna->id);
	}

	/**
	 * Call command according to action
	 * 
	 * @param  string $tnaState 
	 * @param  TNA $tna      
	 * @return 
	 */
	private function doActionForState($tnaState, $tna)
	{
		if($tnaState == 'active'){
			$updatedTna = $this->updatePublishedDate($tna);
            //$itemsOrder = (new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tna->id);
			$itemsOrder = $this->commandBus->execute(new SyncCommand(json_decode($updatedTna->items_order), $updatedTna->id, $updatedTna));
			$updatedTna = $this->saveItemsOrder($updatedTna, $itemsOrder);
			//$updatedTna = $this->tnaProjectedDateCalculator->calculate($updatedTna);
			//$this->commandBus->execute(new TNAPublishActionCommand($updatedTna));
		}
		else if($tnaState == 'paused'){
			$this->commandBus->execute(new TNAPauseActionCommand($tna));
		}
		else if($tnaState == 'completed'){
			//$this->commandBus->execute(new TNAArchiveActionCommand($tna));
		}
		else if($tnaState == 'draft'){
			return;
		}
		else{
			throw new SeException('Wrong state selected', 422, 4200100);
		}
	}

	/**
	 * Update the published Date for TNA
	 * 
	 * @param  TNA $tna 
	 * @return TNA      
	 */
	private function updatePublishedDate($tna)
	{
		if(is_null($tna->published_date)){
			$tna->published_date = $this->today(true);
			if($tna->save())
				return $tna;
			else
				throw new SeException('Error while updating start date for publishing', 500, 50000);
		}
		else{
			return $tna;
		}
	}

	/**
	 * Save items order in TNA
	 * 
	 * @param  TNA $tna        
	 * @param  jsonarray $itemsOrder 
	 * @return TNA             
	 */
	private function saveItemsOrder($tna, $itemsOrder)
	{
		$tna->items_order = json_encode($itemsOrder);
		if($tna->save())
			return $tna;
		else
			throw new SeException('Error while updating itemsOrder for publishing', 500, 50000);
	}

}
