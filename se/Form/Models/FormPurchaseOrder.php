<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormPurchaseOrder extends BaseModel
{
    //k
    use SoftDeletes;

    /**
	 * The mass assignable fields
	 *
	 * @var array
	 * @access protected
	 */
	protected $fillable = [
		'id', 'form_user_id','title','se_issuing_office', 'vendor', 'customer_id', 'ship_to','archived_at',
                'shipping_method', 
                'payment_method',
                'inco_term', 
                'payment_terms',                          
                'factory_ship_date',         
                'factory_cancel_date',       
                'additional_details_as_needed',      
                'date',      
                'authorized_by',
                'po',
                'total_quantity',
                'total',
                'currency',         
                'data'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_purchase_order';

    public static function transform()
    {
       return [
            'title'=>'title',
            'seIssuingOffice' => 'se_issuing_office',
            'vendor' => 'vendor',
            'customerId' => 'customer_id',
            'shipTo' => 'ship_to',
            'shippingMethod' => 'shipping_method', 
            'paymentMethod' => 'payment_method',
            'incoTerm' => 'inco_term',       
            'paymentTerms' => 'payment_terms',
            'factoryShipDate' => 'factory_ship_date',        
            'factoryCancelDate' =>'factory_cancel_date',         
            'additionalDetailsAsNeeded' =>'additional_details_as_needed',        
            'date'=>'date',      
            'authorizedBy'=>'authorized_by',
            'po'=>'po',
            'totalQuantity'=>'total_quantity',
            'total'=>'total',
            'currency'=>'currency',         
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
