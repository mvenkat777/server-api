<?php
namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Form\Commands\SendFormExternalNotificationCommand;
use Platform\Form\Models\Forms;
use Platform\App\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\Form\Jobs\SendFormExternalNotificationJob;

/**
* 
*/
class SubmitFormCommandHandler implements CommandHandler
{
    use DispatchesJobs;
    
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle($command)
    {
        if($command->data['type'] == 'form_sample_invoice' || $command->data['type'] == 'form_production_deposit_invoice' || $command->data['type'] == 'form_production_shipment_invoice' || $command->data['type'] == 'form_proforma_invoice' || $command->data['type'] == 'form_sample_shipment_invoice' || $command->data['type'] == 'form_bulk_shipment_invoice'){
            $table = Helpers::snakeCaseToCamelCase('form_sales_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            $modelUser = 'Platform\Form\Models\FormUser';
        } elseif ($command->data['type'] == 'form_sample_po' || $command->data['type'] == 'form_fabric_po' || $command->data['type'] == 'form_production_po' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_sample_materials_po' || $command->data['type'] == 'form_bulk_materials_po') {
            $table = Helpers::snakeCaseToCamelCase('form_purchase_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            $modelUser = 'Platform\Form\Models\FormUser';
        } elseif ($command->data['type'] == 'form_sample_jobwork_po' || $command->data['type'] == 'form_bulk_jobwork_po') {
            $table = Helpers::snakeCaseToCamelCase('form_production_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            $modelUser = 'Platform\Form\Models\FormUser';
        }
         else {
            $table = Helpers::snakeCaseToCamelCase($command->data['type']);
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            $modelUser = 'Platform\Form\Models\FormUser';
        }
        $formUserId = $modelName::where('id', $command->data['id'])->first();
        if($command->data['submit'] == 'true'){
            $isUpdated = $modelUser::where('id', $formUserId->form_user_id)->update(['submitted_by' => \Auth::user()->id, 'submitted_at' => Carbon::now()->toDateTimeString(), 'form_status_id' => 2]);
            if($isUpdated == true){
                // $command->data['actor'] = \Auth::user();
                // $job = new SendFormExternalNotificationJob($command->data, 'submitted');
                // $this->dispatch($job);
                // $this->commandBus->execute(new SendFormExternalNotificationCommand($command->data, 'submitted'));
            }
            return $isUpdated;
        }
        return false;
    }
}