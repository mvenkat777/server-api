<?php
namespace Platform\Collab\Validators;

use Illuminate\Validation\Factory as Validator;
use Platform\App\Validation\DataValidator;
use Platform\App\Exceptions\SeException;
use App\User;

class CollabValidator extends DataValidator {

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
	public function __construct(Validator $validator){
		$this->validator = $validator;
	}

	/**
	 * Set Validation rule for creating Collab
	 */
	public function setCollabValidation()
	{
		$this->rules = [
		    'title' => 'required|max:50|min:1 ',
		    'description' => 'sometimes',
		    'isPublic' => 'required',
		    'members' => 'required'

		];
		return $this;
	}

	/**
	 * Set Canvas Card validation
	 */
	// public function setPageValidation()
	// {
	// 	$this->rules = [
	// 	    'title' => 'required|max:50|min:1 ',
	// 	    'description' => 'sometimes',
	// 	   	'members' => 'required'
	// 	];
	// 	return $this;
	// }

	/**
	 * Set Canvas Card validation
	 */
	public function setCardValidation()
	{
		$this->rules = [
		    'data' => 'required',
		    'type' => 'required'
		];
		return $this;
	}

	/**
	 * Set Canvas Card Comment validation
	 */
	public function comment(){
		$this->rules = [
		    'data' => 'required'
		];
		return $this;
	}

	/**
	 * Set Canvas Card Comment validation
	 */
	public function validateMember($data){
		if($data['members'][0] == ""){
			throw new SeException("Members cannot be null", 422, 'SE_9001422');
		} else {
			$check = array_search(\Auth::user()->id, $data['members']);
			if($check !== false){
				throw new SeException("Owner cannot add itself. Please check", 422, '9003422');
			}
			return $this;
		}
	}

	/**
	 * Set User Validation
	 */
	public function setUserValidation($userId){
		$ifExists = User::where('id', $userId)->first();
		if($ifExists && ($userId != \Auth::user()->id)){
			return $this;
		}
		else{
			throw new SeException("User Id cannot be used", 422, '9001422');
		}
	}

	public function setDirectMessageValidation()
	{
		$this->rules = [
		    'convId' => 'required',
		    'message' => 'required',
		    'type' => 'required',
		    'isFavourite' => 'required'
		];
		return $this;	
	}
}