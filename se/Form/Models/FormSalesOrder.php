<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormSalesOrder extends BaseModel
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
		'id', 'form_user_id','ship_to_address', 'title','bill_to_address', 'customer_po_number', 'country_of_origin', 'country_of_origin_of_goods', 'final_destination_of_goods', 'mode_of_shipment',
		 'order_date', 'delivery_date', 'port_of_loading', 'cancel_date','tax_id_number','notes','total_discount','sub_total','sales_tax','customer','customer_id','total', 'payment_terms', 'data', 'archived_at', 'invoice_code', 'weight_of_shipment', 'no_of_carton', 'shipping_freight','total_quantity', 'gross_invoice', 'credit_fees','currency','sizes', 'deposit'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_sales_order';

    protected $casts = [
                'total_discount' => 'double',
                'sub_total' => 'double',
                'sales_tax' => 'double',
                'total' => 'double', 
                'shipping_freight' => 'double', 
                'gross_invoice' => 'double', 
                'credit_fees' => 'double', 
                'deposit' => 'double'
            ];

    /**
     * Table Name
     * @var [type]
     */
    public static function transform()
    {
       return [
            'currency'=>'currency',
            'sizes'=>'sizes',
            'totalQuantity'=>'total_quantity',
            'customer'=>'customer',
            'customerId'=>'customer_id',
            'taxIdNumber'=>'tax_id_number',
            'notes'=>'notes',
            'totalDiscount'=>'total_discount',
            'subTotal'=>'sub_total',
            'salesTax'=>'sales_tax',
            'total'=>'total',
            'shipToAddress'=>'ship_to_address',
            'title'=>'title',
            'billToAddress' => 'bill_to_address',
            'customerPoNumber' => 'customer_po_number',
            'countryOfOrigin' => 'country_of_origin',
            'countryOfOriginOfGoods' => 'country_of_origin_of_goods',
            'finalDestinationOfGoods' => 'final_destination_of_goods',
            'modeOfShipment' => 'mode_of_shipment',
            'orderDate' => 'order_date',
            'deliveryDate' => 'delivery_date',
            'portOfLoading' => 'port_of_loading',
            'cancelDate' => 'cancel_date',
            'paymentTerms' => 'payment_terms',
            'invoiceCode' => 'invoice_code',
            'weightOfShipment' => 'weight_of_shipment',
            'noOfCarton' => 'no_of_carton',
            'shippingFreight' => 'shipping_freight',
            'grossInvoice' => 'gross_invoice',
            'creditFees' => 'credit_fees',
            'deposit' => 'deposit',
            'data' => 'data'
        ];
    }

    public function formUser()
    {
        return $this->belongsTo('Platform\Form\Models\FormUser', 'form_user_id');
    }

	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
            'formUserSO' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
