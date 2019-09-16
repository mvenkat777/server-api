<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class Forms extends BaseModel
{
    //
    use SoftDeletes;

    /**
	 * The mass assignable fields
	 *
	 * @var array
	 * @access protected
	 */
	protected $fillable = [
		'id', 'form_name', 'form_table_name'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'forms';	
}
