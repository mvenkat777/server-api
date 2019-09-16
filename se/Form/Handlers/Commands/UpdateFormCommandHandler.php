<?php
namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Form\Models\Forms;
use Platform\App\Helpers\Helpers;
use Carbon\Carbon;

/**
* 
*/
class UpdateFormCommandHandler implements CommandHandler
{
    public function handle($command)
    {
        if($command->data['type'] == 'form_sample_invoice' || $command->data['type'] == 'form_production_deposit_invoice' || $command->data['type'] == 'form_production_shipment_invoice' || $command->data['type'] == 'form_proforma_invoice' || $command->data['type'] == 'form_sample_shipment_invoice' || $command->data['type'] == 'form_bulk_shipment_invoice'){
            $command->data['type'] = 'form_sales_order';
        } elseif ($command->data['type'] == 'form_sample_po' || $command->data['type'] == 'form_fabric_po' || $command->data['type'] == 'form_production_po' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_sample_materials_po' || $command->data['type'] == 'form_bulk_materials_po') {
            $command->data['type'] = 'form_purchase_order';
        } elseif ($command->data['type'] == 'form_sample_jobwork_po' || $command->data['type'] == 'form_bulk_jobwork_po') {
            $command->data['type'] = 'form_production_order';
        }
        $table = Helpers::snakeCaseToCamelCase($command->data['type']);
        $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        $modelUser = 'Platform\Form\Models\FormUser';
        $input = [];
        $convertKey = ['data','seIssuingOffice', 'vendor', 'customerId', 'sizes'];
            foreach ($command->data as $key => $value) {
                if(isset($modelName::transform()[$key])){
                    if(in_array($key, $convertKey))
                    {
                        $input[$modelName::transform()[$key]] = json_encode($value);
                    }else{
                        $input[$modelName::transform()[$key]] = $value;
                    }                
                }
            }
        $input['updated_at'] = Carbon::now()->toDateTimeString();
        // \DB::beginTransaction();
            if(\DB::table($command->data['type'])->where('id', $command->data['id'])->update($input)){
                $formUserId = $modelName::where('id', $command->data['id'])->first();
                return $modelUser::where('id', $formUserId->form_user_id)->update(['updated_by' => \Auth::user()->id]);
            } else {
                return false;
            }
        // \DB::commit();
    }
}