<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormCommercialInvoice extends BaseModel
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
		'id','title', 'form_user_id', 'exporter', 'consignee', 'notify_party', 'loading_port', 'destination',  'carrier',
        'sailing_on', 'invoice_number', 'invoice_date', 'origin_remark', 'shipment_remark', 'freight_remark',
        'total_qty', 'total_package', 'total_net_weight', 'total_gross_weight', 'total_measurement','in_words', 'data', 
        'archived_at'];

    public static function transform()
        {
           return [
                'inWords'=>'in_words',
                'title'=>'title',
                'exporter' => 'exporter',
                'consignee' => 'consignee',
                'notifyParty' => 'notify_party',
                'loadingPort' => 'loading_port',
                'destination' => 'destination',
                'carrier' => 'carrier',
                'sailingOn' => 'sailing_on',
                'invoiceNumber' => 'invoice_number',
                'invoiceDate' => 'invoice_date',
                'originRemark' => 'origin_remark',
                'shipmentRemark' => 'shipment_remark',
                'freightRemark' => 'freight_remark',
                'totalQty' => 'total_qty',
                'totalPackage' => 'total_package',
                'totalNetWeight' => 'total_net_weight',
                'totalGrossWeight' => 'total_gross_weight',
                'totalMeasurement' => 'total_measurement',
                'data' => 'data'
            ];
        }

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_commercial_invoice';



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserCI' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];


}
