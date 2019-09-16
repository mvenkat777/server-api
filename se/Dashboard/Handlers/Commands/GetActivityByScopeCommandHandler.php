<?php

namespace Platform\Dashboard\Handlers\Commands;

use App\ImmediateActionRule;
use Platform\App\Activity\Models\GlobalActivity;
use Platform\App\Commanding\CommandHandler;

class GetActivityByScopeCommandHandler implements CommandHandler 
{

	public function __construct()
	{

	}

	public function handle($command)
	{
		if ($command->type === 'me') {
	        return GlobalActivity::orderBy('created_at', 'desc')
	        	->where('actor.user.email', \Auth::user()->email)
	        	->paginate($command->items);
		}
		return GlobalActivity::orderBy('created_at', 'desc')
	        	->paginate($command->items); 
	}

}
