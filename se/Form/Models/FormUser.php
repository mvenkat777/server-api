<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Form\Models\Forms;
// use Illuminate\Database\Eloquent\Model;

class FormUser extends BaseModel
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
		'id', 'form_name_id', 'form_status_id', 'created_by', 'updated_by', 'submitted_by', 'submitted_at', 'approval_request_for',
		 'is_approved', 'approved_at', 'approved_by', 'is_rejected', 'rejected_at', 'rejected_by', 'remark', 'archived_at', 'is_editable'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_user';



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formName' => [
            'modelField' => 'form_name_id',
            'relation' => 'Platform\Form\Models\Forms',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ],
        'formStatus' => [
            'modelField' => 'form_status_id',
            'relation' => 'Platform\Form\Models\FormStatus',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];

    public function forms(){ 
        //$form_id = FormUser::find($user)->pluck('form_name_id');
        //return Forms::find($form_id)->pluck('form_name');
        // return $this->hasOne('Platform\Form\Models\Forms', 'id', 'form_name_id')->whereNull('deleted_at');
        return $this->belongsTo('Platform\Form\Models\Forms', 'form_name_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function submittor()
    {
        return $this->belongsTo('App\User', 'submitted_by');
    }
    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo('App\User', 'rejected_by');
    }

    public function approvalRequestor()
    {
        return $this->belongsTo('App\User', 'approval_request_for');
    }

    // public function formSales()
    // {
    //     return $this->hasMany('Platform\Form\Models\FormSalesOrder', 'techpack_id', 'id');
    // }
    // public function vendor(){
    //     return $this->hasOne('App\Vendor', 'id', 'vendor_id')->whereNull('deleted_at');
    // }


}
