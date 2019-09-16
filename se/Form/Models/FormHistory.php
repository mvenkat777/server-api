<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Form\Models\Forms;
// use Illuminate\Database\Eloquent\Model;

class FormHistory extends BaseModel
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
        'id', 'his_form_user_olddata', 'his_form_user_newdata', 'his_form_olddata', 'his_form_newdata', 'trigger_table', 'created_at', 'updated_at',
         'deleted_at'];

    /**
     * Table Name
     * @var [type]
     */
    protected $table = 'form_history';
}
