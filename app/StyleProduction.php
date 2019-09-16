<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StyleProduction extends Model
{
    /**
	 * The mass assignable fields
	 * 
	 * @var array
	 * @access protected
	 */
	protected $fillable = ['name', 'owner', 'is_parallel'];

	/**
	 * Table Name
	 * @var [type]
	 */
	protected $table = 'style_productions';
}
