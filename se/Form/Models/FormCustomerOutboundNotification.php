<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormCustomerOutboundNotification extends BaseModel
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
		'id', 'form_user_id','title', 'vendor', 'customer', 'customer_po', 'se_po', 'total_shipped', 'ex_factory_date', 'eta_customer',  'archived_at', 'data'];

    public static function transform()
    {
       return [
            'title' =>'title',
            'vendorId' => 'vendor',
            'customerId' => 'customer',
            'customerPo' => 'customer_po',
            'sePo' => 'se_po',
            'totalShipped' => 'total_shipped',
            'exFactoryDate' => 'ex_factory_date',
            'etaCustomer' => 'eta_customer',
            'data' => 'data'
        ];
    }

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_customer_outbound_notification';



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserCON' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
