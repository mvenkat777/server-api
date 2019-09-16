<?php

namespace Platform\App\Validation;

use Platform\App\Helpers\Helpers;
use Carbon\Carbon;

class CommandValidator
{
    public function isLessThanToday($date)
    {
		if(Carbon::parse($date)->toDateTimeString() < Helpers::today())
			return true;
		else
			return false;
    }

}

