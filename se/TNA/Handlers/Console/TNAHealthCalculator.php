<?php

namespace Platform\TNA\Handlers\Console;

use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\TNA\Helpers\TNAHelper;

class TNAHealthCalculator 
{
	/**
	 * Allowed diffence between target and projected date
	 */
	const ALLOWED_DIFF_TARGET_PROJECTED = 7;

	/**
	 * Calculate TNA Health of a TNA
	 * 
	 * @param  TNA Model $tna 
	 * @return TNA Model      
	 */
	public function calculate($tna)
	{
		if($this->tnaHealthIsDanger($tna)){
			return $this->setTNAHealth($tna, 'danger');
		}
		if($this->tnaHealthIsWarning($tna)){
			return $this->setTNAHealth($tna, 'warning');
		}
		return $this->setTNAHealth($tna, 'normal');
	}

	/**
	 * Check if TNA Health is danger or not
	 * 
	 * @param  TNA Model $tna 
	 * @return boolean      
	 */
	private function tnaHealthIsDanger($tna)
	{
        return ($tna->projected_date > $tna->target_date) && (TNAHelper::diffInDates($tna->target_date, $tna->projected_date) > self::ALLOWED_DIFF_TARGET_PROJECTED);
	}

	/**
	 * Check if TNA health is at waring or not
	 * 
	 * @param  TNA Model $tna 
	 * @return boolean      
	 */
	private function tnaHealthIsWarning($tna)
	{
        return ($tna->projected_date > $tna->target_date) && (TNAHelper::diffInDates($tna->target_date, $tna->projected_date) < self::ALLOWED_DIFF_TARGET_PROJECTED);
	}

	/**
	 * Set TNA health of TNA
	 * 
	 * @param TNA $tna    
	 * @param string $health 
	 * @return  TNA 
	 */
	private function setTNAHealth($tna, $health)
	{
		$tna->tna_health_id = TNAHelper::getTNAHealthId($health);
        $tna = $tna->setRelation('health', $tna->health()->first());
		if($tna->save()){
			return $tna;
		}
		return $tna;
	}

}
