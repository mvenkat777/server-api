<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormProductionOrder extends BaseModel
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
		'id', 'form_user_id','title', 'se_issuing_office', 'vendor', 'customer_id', 'ship_to', 'po_date','po',  'archived_at',
                'shipping_method', 
                'shipping_terms',
                'inco_term',         
                'sizes',         
                'payment_terms',         
                'factory_ship_date',         
                'factory_cancel_date',       
                'additional_details_as_needed',      
                'date',      
                'authorized_by',         
                'data',
                'total_quantity',
                'total',
                'line_order_ref',
                'currency',
                'payment_method'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_production_order';

    public static function transform()
    {
       return [
            'title'=>'title',
            'poDate'=>'po_date',
            'po'=>'po',
            'seIssuingOffice' => 'se_issuing_office',
            'vendor' => 'vendor',
            'customerId' => 'customer_id',
            'shipTo' => 'ship_to',
            'shippingMethod' => 'shipping_method', 
            'shippingTerms' => 'shipping_terms',
            'incoTerm' => 'inco_term',       
            'sizes'=>'sizes', 
            'paymentTerms' =>'payment_terms',        
            'factoryShipDate' => 'factory_ship_date',        
            'factoryCancelDate' =>'factory_cancel_date',         
            'additionalDetailsAsNeeded' =>'additional_details_as_needed',        
            'date'=>'date',      
            'authorizedBy'=>'authorized_by',
            'totalQuantity'=>'total_quantity',
            'total'=>'total',
            'lineOrderRef'=>'line_order_ref',
            'currency'=>'currency',
            'paymentMethod'=>'payment_method',
            'data'=>'data'

        ];
    }



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserPO' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
