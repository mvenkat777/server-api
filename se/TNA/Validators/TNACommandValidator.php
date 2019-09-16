<?php

namespace Platform\TNA\Validators;

use Carbon\Carbon;
use Platform\App\Validation\DataValidator;
use Platform\TNA\Models\TNA;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Validators\TNAValidator;

class TNACommandValidator 
{
	/**
	*@var array
	*/
	protected $rules = [];

	/**
	 * @var Platform\TNA\Validators\TNAValidator
	 */
	protected $tnaValidator;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

	/**
	 * @param TNAValidator  $tnaValidator  
	 * @param TNARepository $tnaRepository 
	 */
	public function __construct(TNAValidator $tnaValidator,
								TNARepository $tnaRepository)
	{
		$this->tnaValidator = $tnaValidator;
		$this->tnaRepository = $tnaRepository;
	}

	/**
	 * Check given date is less than today
	 * 
	 * @param  DATE  $date 
	 * @return boolean       
	 */
	protected function isLessThanToday($date)
	{
		if($date < $this->today())
			return true;
		else
			return false;
	}

	/**
	 * Get today's date
	 * 
	 * @param  boolean $wantObject 
	 * @return Object/String              
	 */
	protected function today($wantObject = false)
	{
		if($wantObject)
			return Carbon::now();
		else
			return Carbon::now()->toDateTimeString();
	}

	/**
	 * Check Validation for Publishing TNA
	 * 
	 * @param  Command $command 
	 * @return boolean          
	 */
	protected function validateChangeStatus($command)
	{
		if($command->tnaState === 'completed'){
			$tna = TNA::find($command->tnaId);
			$itemsOrder = json_decode($tna->items_order);
			foreach ($itemsOrder as $key => $itemOrder) {
				if($itemOrder->itemStatus !== 'closed') {
					return false;
				}
			}
			return true;
		}
		return true;
	}

	/**
	 * Check if TNA is completed
	 * 
	 * @param  UUID $tnaId 
	 * @return boolean        
	 */
	protected function tnaIsArchived($tnaId)
	{
		$tna = TNA::find($tnaId);
		return $tna->state->state === 'completed';
	}

    protected function validate($command)
    {
        dd(get_class($this));
    }

}
