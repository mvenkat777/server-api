<?php
namespace Platform\Form\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Mailer\Mailer;
use Platform\App\Exceptions\SeException;
use Platform\Form\Models\Forms;
use Platform\Form\Models\FormUser;
use Platform\App\Helpers\Helpers;
use App\Role;
use App\Group;
/**
* 
*/
class SendFormExternalNotificationCommandHandler extends Mailer implements CommandHandler
{
    public function handle($command)
    {
        if(getenv('APP_ENV') == 'staging'){
            return;
        }
        try{
            if(getenv('APP_ENV') != 'local'){
                $command->data['link'] = $this->generateLink();
                $command->data['email'] = json_decode(json_encode(['email' => $this->getGroupReceiver($command->action)]));
                $subject = $this->generateEmailSubject($command);
                if($command->action == 'rejected'){
                    $command->data['email'] = json_decode(json_encode(['email' => $command->data['email']]));
                }
                $this->sendToUser($command->data['email'], $subject, $command->data['emailPath'], $command);
            }
        }catch(\Exception $e){
            return;
        }
    }

    public function generateEmailSubject($data)
    {
        if($command->data['type'] == 'form_sample_invoice' || $command->data['type'] == 'form_production_deposit_invoice' || $command->data['type'] == 'form_production_shipment_invoice' || $command->data['type'] == 'form_proforma_invoice' || $command->data['type'] == 'form_sample_shipment_invoice' || $command->data['type'] == 'form_bulk_shipment_invoice'){
            $table = Helpers::snakeCaseToCamelCase('form_sales_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        } elseif ($command->data['type'] == 'form_sample_po' || $command->data['type'] == 'form_fabric_po' || $command->data['type'] == 'form_production_po' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_freight_estimate' || $command->data['type'] == 'form_sample_materials_po' || $command->data['type'] == 'form_bulk_materials_po') {
            $table = Helpers::snakeCaseToCamelCase('form_purchase_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        } elseif ($command->data['type'] == 'form_sample_jobwork_po' || $command->data['type'] == 'form_bulk_jobwork_po') {
            $table = Helpers::snakeCaseToCamelCase('form_production_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        }
         else {
            $table = Helpers::snakeCaseToCamelCase($data->data['type']);
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        }
        if($data->action == 'submitted'){
            $data->data['formType'] = Forms::where('form_table_name', $data->data['type'])->first()->form_type;
            $data->data['formTitle'] = $modelName::where('id', $data->data['id'])->first()->title;
            $data->data['emailPath'] = 'emails.forms.FormApprovalRequestMail';
            $data->data['link'] = $data->data['link'].'submitted';
            return (getenv('APP_ENV') == 'local') ? '(Staging Testing) Request for Approval - '.$data->data['formType'].' - '.$data->data['formTitle'] : 'Request for Approval - '.$data->data['formType'].' - '.$data->data['formTitle'];
        }
        if($data->action == 'approved'){
            $data->data['formType'] = Forms::where('form_table_name', $data->data['type'])->first()->form_type;
            $data->data['formTitle'] = $modelName::where('id', $data->data['id'])->first()->title;
            $data->data['emailPath'] = 'emails.forms.FormApprovedMail';
            $data->data['link'] = $data->data['link'].'approved';
            return (getenv('APP_ENV') == 'staging') ? '(Staging Testing) Approved - '.$data->data['formType'].' - '.$data->data['formTitle'] : 'Approved - '.$data->data['formType'].' - '.$data->data['formTitle'];
        }
        if($data->action == 'rejected'){
            $form = Forms::where('form_table_name', $data->data['type'])->first();
            $modelCollection = $modelName::where('id', $data->data['id'])->first();
            $data->data['formType'] = $form->form_type;
            $data->data['formTitle'] = $modelCollection->title;
            $data->data['email'] = \App\User::where('id', FormUser::where('id', $modelCollection->form_user_id)->first()->created_by)->first()->email;
            $data->data['emailPath'] = 'emails.forms.FormRejectedMail';
            $data->data['link'] = $data->data['link'].'pending';
            return (getenv('APP_ENV') == 'staging') ? '(Staging Testing) Rejected - '.$data->data['formType'].' - '.$data->data['formTitle'] : 'Rejected - '.$data->data['formType'].' - '.$data->data['formTitle'];
        }
    }

    protected function getGroupReceiver($action = NULL)
    {
        if($action == 'submitted')
        {
            $group = Group::where('name', 'FormApproverUser')->first();
            if($group)
            {
                $groupId = $group->id;
                $role = Role::where('group_id', $groupId)->first();
                if($role)
                {
                    return array_column($role->users->toArray(), 'email');
                }
            }
        }
        if($action == 'approved')
        {
            $group = Group::where('name', 'FormApproverUser')->first();
            if($group)
            {
                $groupId = $group->id;
                $role = Role::where('group_id', $groupId)->get();
                if($role[1])
                {
                    return array_column($role[1]->users->toArray(), 'email');
                }
            }
        }
    }

    public function generateLink()
    {
        if(getenv('APP_ENV') == 'local')
            return 'http://platform.dev/#/forms/list/';
        elseif(getenv('APP_ENV') == 'staging')
            return 'http://platform.sourc.in/#/forms/list/';
        elseif(getenv('APP_ENV') == 'production')
            return 'http://platform.sourceeasy.com/#/forms/list/';
    }
}