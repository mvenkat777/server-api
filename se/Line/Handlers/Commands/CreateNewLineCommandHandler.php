<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Validators\LineValidators;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\NamingEngine\Commands\GenerateLineCodeCommand;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;


class CreateNewLineCommandHandler implements CommandHandler
{   
	 use DispatchesJobs;

	/**
	 * @param LineValidators $validator
	 * @param LineRepository $line
	 * @param CustomerRepository $customer
	 * @param DefaultCommandBus $commandBus
	 *
	 * @return void
	 */
	public function __construct(
		LineValidators $validator,
		LineRepository $line,
		CustomerRepository $customer,
		DefaultCommandBus $commandBus,
		DefaultRuleBus $defaultRuleBus
	) {
		$this->validator = $validator;
		$this->line = $line;
		$this->customer = $customer;
		$this->commandBus = $commandBus;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	/**
	 * Handles CreateNewLineCommand
	 *
	 * @param CreateNewLineCommand $command
	 *
	 * @return mixed
	 */
	public function handle($command)
	{
		$data = $command->data;
		$data['customerCode'] = $this->customer->getCodeById($data['customerId']);
        $data['code'] = $this->commandBus->execute(new GenerateLineCodeCommand($data['customerCode']));
		$this->validator->setCreationRules()->validate($data);
		$line = $this->line->createNewLine($data);
		
		// $receiverList = [];

		$line->customerName = $line->customer->name;
		// $this->defaultRuleBus->execute($line->replicate(), \Auth::user(), 'CreateNewLine');
		// $job = (new DefaultRuleBusJob($line, \Auth::user(), 'CreateNewLine'));
  //        $this->dispatch($job);

		// $line->salesRepresentativeEmail = $line->salesRepresentative->email;
		// array_push($receiverList, $line->salesRepresentative->email);
		// array_push($receiverList, $line->productionLead->email);
		// array_push($receiverList, $line->productDevelopmentLead->email);

		// if (!is_null($line->merchandiser)) {
		// 	array_push($receiverList, $line->merchandiser->email);
		// }
		// $line->creatorName = \Auth::user()->display_name;
		// $line->emailSubject = 'line '.$line->name.' created for '.$line->customer->name;

		// $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://platform.sourceeasy.com';
		// $line->link = $origin.'/#/line/'.$line->id;
		// $receiver = array_values(array_unique($receiverList));
		
		// foreach ($receiver as $key => $value) {
		// 	if($value !== \Auth::user()->email){
		// 		$line->userName = \App\User::where('email',$value)->first()->display_name;
		// 		$this->defaultRuleBus->setReceiver([$value])
	 //                            ->setItemURL("line/$line->id")
	 //                            ->setEntityName("line")
		// 						->setItemAction('create')
		// 						->execute('NotificationOnLineCreation', $line);
		// 		}
		// 	$this->defaultNotificationBus->setReceiver([$value])
  //                                   ->setItemURL($line->id)
  //                                   ->setEntityName("line")
  //                                   ->setAction('create')
  //                                   ->setActor(\Auth::user())
  //                                   ->setData(['line' => (array)json_decode(json_encode($line), true)])
  //                                   ->execute();  
		// }

		return $line;
	}

}
