<?php

namespace Platform\Techpacks\Jobs;


use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Platform\App\Commanding\CommandTranslator;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Queuing\Contracts\QueueAction;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Commands\MultipleTechpackExportCommand;
use Platform\Techpacks\Repositories\Eloquent\EloquentTechpackRepository;



class MultipleExportJob extends QueueAction
{
	public function setUp()
	{
		$this->app = new Application();
		// $this->app->boot();
		$this->app->make(Repository::class);
		$this->app->instance(
			'Platform\Techpacks\Repositories\Contracts\TechpackRepository',
			new EloquentTechpackRepository
		);

		$this->commandBus = $this->app->make(DefaultCommandBus::class);
	}
		
	public function perform()
	{
		try {
			$request = $this->args['request'];
			$request['email'] = $this->args['email'];
			
				$command = new MultipleTechpackExportCommand(
                    $request,
                    $request['techpackIds']
                );
			$this->commandBus->execute(
                $command
            );
		} catch (Exception $e) {
			dd($e);
		}
		
		
	}
}