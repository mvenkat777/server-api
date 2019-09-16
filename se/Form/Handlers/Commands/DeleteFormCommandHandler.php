<?php
namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Form\Models\Forms;
use Platform\App\Helpers\Helpers;
use Carbon\Carbon;

/**
* 
*/
class DeleteFormCommandHandler implements CommandHandler
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
        // dd($command->data['id']);
        $form = $modelName::where('id', $command->data['id'])->first();
        $formUser = $modelUser::where('id', $form->form_user_id)->first();
        if($formUser->created_by == $command->creator)
        {
            if($form->delete())
            {
                $formUser->delete();
            }
            return true;
        }
        else
        {
            return false;
        }
        
        
    }
}