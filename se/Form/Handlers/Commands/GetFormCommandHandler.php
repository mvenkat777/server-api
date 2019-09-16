<?php
namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Helpers\Helpers;

/**
* 
*/
class GetFormCommandHandler implements CommandHandler
{
    public function handle($command)
    {
        if($command->type == 'all'){
           
            return \Platform\Form\Models\FormUser::orderBy('created_at','desc')->orderBy('updated_at','desc')->get();
        }else{
            if($command->type == 'form_sample_invoice' || $command->type == 'form_production_deposit_invoice' || $command->type == 'form_production_shipment_invoice' || $command->type == 'form_proforma_invoice' || $command->type == 'form_sample_shipment_invoice' || $command->type == 'form_bulk_shipment_invoice'){
                $clsName = ucfirst(Helpers::snakeCaseToCamelCase('form_sales_order'));
                $model = 'Platform\Form\Models\\'.$clsName;
            } elseif ($command->type == 'form_sample_po' || $command->type == 'form_fabric_po' || $command->type == 'form_production_po' || $command->type == 'form_freight_estimate' || $command->type == 'form_freight_estimate' || $command->type == 'form_sample_materials_po' || $command->type == 'form_bulk_materials_po') {
                $clsName = ucfirst(Helpers::snakeCaseToCamelCase('form_purchase_order'));
                $model = 'Platform\Form\Models\\'.$clsName;
            } elseif ($command->type == 'form_sample_jobwork_po' || $command->type == 'form_bulk_jobwork_po') {
                $clsName = ucfirst(Helpers::snakeCaseToCamelCase('form_production_order'));
                $model = 'Platform\Form\Models\\'.$clsName;
            }

            else {
                $clsName = ucfirst(Helpers::snakeCaseToCamelCase($command->type));
                $model = 'Platform\Form\Models\\'.$clsName;
            }
            if(isset($command->id)){
                return $model::where('id','=',$command->id)->get();
            }else{
                return $model::orderBy('created_at','desc')->orderBy('updated_at','desc')->get();
            }            
        }
    }
}