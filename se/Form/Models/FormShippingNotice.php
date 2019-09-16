<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormShippingNotice extends BaseModel
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
		'id', 'form_user_id','title', 'bill_to_address', 'customer_po_number', 'delivery_date', 'ship_to_address', 'origin_country_goods', 'destination_country', 'shipment_mode', 'origin_country',
		 'cancel_date', 'created_by','notes','created_by_date', 'archived_at', 'data'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_shipping_notice';

    /**
     * Table Name
     * @var [type]
     */
    public static function transform()
    {
       return [
            'notes'=>'notes',
            'title'=>'title',
            'billToAddress' => 'bill_to_address',
            'customerPoNumber' => 'customer_po_number',
            'deliveryDate' => 'delivery_date',
            'shipToAddress' => 'ship_to_address',
            'originCountryGoods' => 'origin_country_goods',
            'destinationCountry' => 'destination_country',
            'shipmentMode' => 'shipment_mode',
            'originCountry' => 'origin_country',
            'cancelDate' => 'cancel_date',
            'createdBy' => 'created_by',
            'createdByDate' => 'created_by_date',
            'data' => 'data'
        ];
    }



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserSN' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
