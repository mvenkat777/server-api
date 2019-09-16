<?php

namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Form\Models\Forms;
use Platform\App\Helpers\Helpers;
use Rhumsaa\Uuid\Uuid;
use Platform\NamingEngine\Commands\GenerateOrderCodeCommand;
use Platform\App\Commanding\DefaultCommandBus;
// use Platform\Form\Repositories\Contracts\FormRepository;

/**
* 
*/
class StoreFormCommandHandler implements CommandHandler
{

    public $formRepository;

    public $commandBus;

    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle($command)
    {
       // dd($command->data['vendor']['vendorId']);
       // dd($command->data['seIssuingOffice']['location']['city']);
       // dd($command->data['type']);
        if($command->data['type'] == 'form_sample_invoice' || $command->data['type'] == 'form_production_deposit_invoice' || $command->data['type'] == 'form_production_shipment_invoice' || $command->data['type'] == 'form_proforma_invoice' || $command->data['type'] == 'form_sample_shipment_invoice' || $command->data['type'] == 'form_bulk_shipment_invoice'){
            // $command->data['type'] = 'form_sales_order';
            $table = Helpers::snakeCaseToCamelCase('form_sales_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        } elseif ($command->data['type'] == 'form_sample_po' || $command->data['type'] == 'form_fabric_po' || $command->data['type'] == 'form_production_po' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_sample_materials_po' || $command->data['type'] == 'form_bulk_materials_po') {
            $table = Helpers::snakeCaseToCamelCase('form_purchase_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            // $command->data['type'] = 'form_purchase_order';
        } elseif ($command->data['type'] == 'form_sample_jobwork_po' || $command->data['type'] == 'form_bulk_jobwork_po') {
            $table = Helpers::snakeCaseToCamelCase('form_production_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        }
         else {
            $table = Helpers::snakeCaseToCamelCase($command->data['type']);
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        }
        $modelUser = 'Platform\Form\Models\FormUser';
        $input = [];
        $input['id'] = Uuid::uuid4()->toString();

        /*
        Generate Purchase order code
         */
        if ($command->data['type'] == 'form_sample_po' || $command->data['type'] == 'form_fabric_po' || $command->data['type'] == 'form_production_po' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_production_order' || $command->data['type'] == 'form_sample_materials_po' || $command->data['type'] == 'form_bulk_materials_po' || $command->data['type'] == 'form_sample_jobwork_po' || $command->data['type'] == 'form_bulk_jobwork_po')
        {

            $input['po'] = $this->commandBus->execute(new GenerateOrderCodeCommand($command->data['seIssuingOffice']['location']['code'], $command->data['vendor']['code'], 'PO'));
        }
        elseif ($command->data['type'] == 'form_sample_invoice' || $command->data['type'] == 'form_production_deposit_invoice' || $command->data['type'] == 'form_production_shipment_invoice' || $command->data['type'] == 'form_sample_shipment_invoice' || $command->data['type'] == 'form_bulk_shipment_invoice' || $command->data['type'] == 'form_proforma_invoice') 
        {
            $input['invoice_code'] = $this->commandBus->execute(new GenerateOrderCodeCommand('NA', $command->data['customerId']['code'], 'INV'));
        }
        
        $user = $modelUser::create($this->updateFormUser(Forms::where('form_table_name',$command->data['type'])->pluck('id')));
        if($user){
            $input['form_user_id'] = $user->id;
            $convertKey = ['data','seIssuingOffice', 'vendor', 'customerId', 'sizes'];
            foreach ($command->data as $key => $value) {
                if(isset($modelName::transform()[$key])){
                    // var_dump($key);
                    if(in_array($key, $convertKey))
                    {
                        // dd(json_encode($value));
                        $input[$modelName::transform()[$key]] = json_encode($value);
                    }else{
                        $input[$modelName::transform()[$key]] = $value;
                    }                
                }
            }
           // dd($input);
            if($modelName::create($input)){
                return true;
            } else {
                return false;
            }
        }
    }

    public function updateFormUser($value)
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'form_name_id' => $value,
            'form_status_id' => 1,
            'created_by' => \Auth::user()->id
        ];
    }
}