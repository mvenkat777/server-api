<?php
namespace Platform\DirectMessage\Validators;

use Illuminate\Validation\Factory as Validator;
use Platform\App\Validation\DataValidator;

/**
* 
*/
class MessageValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

	/**
	 * Inherites property, but needed for setting rules and calling validate()
	 * @var Validator
	 */
	protected $validator;

	/**
	 * @param Validator $validator 
	 */
	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Set Validation rule for creating Direct Message
	 */
	public function validateInput($data)
	{
		if($data['message'] ==  NULL)
		{
			return 0;
		}
		if($data['type'] ==  NULL)
		{
			return 0;
		}
		return 1;
	}
}