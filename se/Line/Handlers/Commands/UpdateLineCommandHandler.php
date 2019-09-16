<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Validators\LineValidators;

class UpdateLineCommandHandler implements CommandHandler 
{

	/**
	 * @param LineRepository $line
	 * @param LineValidators $validator
	 *
	 * @return void
	 */
	public function __construct(LineRepository $line, LineValidators $validator)
	{
		$this->line = $line;
		$this->validator = $validator;
	}

	/**
	 * Handles the UpdateLineCommand 
	 *
	 * @param string $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$data = $command->data;
		$this->validator->setUpdationRules()->validate($data);

		return $this->line->updateLine($command->lineId, $data);
	}

}
