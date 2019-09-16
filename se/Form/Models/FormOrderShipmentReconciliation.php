<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormOrderShipmentReconciliation extends BaseModel
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
		'id', 'form_user_id','title', 'customer', 'purchase_order','data', 'archived_at'];

    public static function transform()
    {
       return [
            'data'=>'data',
            'title'=>'title',
            'customerId' => 'customer',
            'purchaseOrder' => 'purchase_order',
        ];
    }

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_order_shipment_reconciliation';



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserOSR' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
