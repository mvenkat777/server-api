<?php
namespace Platform\App\Models;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

/**
* BaseModel for every models
*/
abstract class BaseModel extends Model
{
	use ActivityRecorder;

	public function getVerbs()
	{
		return $this->verbs;
	}

	public function getModelVerbs()
	{
		return $this->modelVerb;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function getValues()
	{
		return $this->values;
	}

	public function getRelations()
	{
		return $this->relation;
	}
}