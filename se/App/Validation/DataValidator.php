<?php

namespace Platform\App\Validation;

use Illuminate\Validation\Factory as Validator;

abstract class DataValidator
{
	/**
	 * @var Validator
	 */
	protected $validator;

	/**
	 * @var [type]
	 */
	protected $validation;

	/**
	 * @param Validator $validator
	 */
	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	/**
	 *
	 * @param  array  $data
	 * @throws DataValidationException
	 */
	public function validate(array $data)
	{
		$this->validation = $this->validator->make($data, $this->getValidationRules());
		if ($this->validation->fails()) {
			throw new DataValidationException($this->getValidateMessage(), $this->getValidationErrors());
		}
	}

	/**
	 * @return array
	 */
	protected function getValidationRules()
	{
		return $this->rules;
	}

	/**
	 * @return array
	 */
	protected function getValidationErrors()
	{
		return $this->validation->errors();
	}


	/**
	 * @return string
	 */
	protected function getValidateMessage()
	{
		return $this->validation->messages()->first();
	}
}
