<?php

namespace Platform\Form\Models;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Model;

class FormActualPackingList extends BaseModel
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
		'id', 'form_user_id', 'title','factory_name', 'factory_address', 'container_no', 'seal_no', 'shipping_date',  'made_in',
        'packing_date', 'etd_vn', 'eta_sf', 'vessel_name', 'ship_to', 'attn',
        'style', 'po', 'quantity', 'description', 
        'archived_at', 'data'];

	/**
     * Table Name
     * @var [type]
     */
	protected $table = 'form_actual_packing_list';



	/**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'formUserAPL' => [
            'modelField' => 'form_user_id',
            'relation' => 'Platform\Form\Models\FormUser',
            'operation' => 'ILIKE',
            'foreignField' => 'id'
        ]
        
    ];

    public static function transform()
    {
       
        return [
            'factoryName'=>'factory_name',
            'factoryAddress'=>'factory_address',
            'containerNo' => 'container_no',
            'sealNo' => 'seal_no',
            'shippingDate' => 'shipping_date',
            'madeIn' => 'made_in',
            'packingDate' => 'packing_date',
            'etdVn' => 'etd_vn',
            'etaSf' => 'eta_sf',
            'vesselName' => 'vessel_name',
            'shipTo' => 'ship_to',
            'attn' => 'attn',
            'style' => 'style',
            'po' => 'po',
            'quantity' => 'quantity',
            'description' => 'description',
            'data' => 'data'
        ];
    }


}
