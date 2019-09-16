<?php

namespace Platform\Form\Transformers;

use Platform\Form\Models\FormUser;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;
use League\Fractal\Manager;
use Platform\App\Helpers\Helpers;
use Platform\Form\Transformers\FormTransformer;

class FormUserTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }
    public function transform(FormUser $formUser)
    {
        $creator = $this->item($formUser->creator, new MetaUserTransformer);
        $creator = $this->manager->createData($creator)->toArray();
        if(isset($formUser->updated_by)){
            $updator = $this->item($formUser->updator, new MetaUserTransformer);
            $updator = $this->manager->createData($updator)->toArray();
        }
        if(isset($formUser->submitted_by)){
            $submittor = $this->item($formUser->submittor, new MetaUserTransformer);
            $submittor = $this->manager->createData($submittor)->toArray();
        }
        if(isset($formUser->approved_by)){
            $approver = $this->item($formUser->approver, new MetaUserTransformer);
            $approver = $this->manager->createData($approver)->toArray();
        }
        if(isset($formUser->rejected_by)){
            $rejector = $this->item($formUser->rejector, new MetaUserTransformer);
            $rejector = $this->manager->createData($rejector)->toArray();
        }
        if(isset($formUser->approval_request_for)){
            $approval_requestor = $this->item($formUser->approvalRequestor, new MetaUserTransformer);
            $approval_requestor = $this->manager->createData($approval_requestor)->toArray();
        }
        $tableName = \Platform\Form\Models\Forms::where('id', '=' , $formUser->form_name_id)->pluck('form_table_name') ;
         if($tableName == 'form_sample_invoice' || $tableName == 'form_production_deposit_invoice' || $tableName == 'form_production_shipment_invoice' || $tableName == 'form_proforma_invoice' || $tableName == 'form_sample_shipment_invoice' || $tableName == 'form_bulk_shipment_invoice'){
            $tableName = 'form_sales_order';
        } elseif ($tableName == 'form_sample_po' || $tableName == 'form_fabric_po' || $tableName == 'form_production_po' || $tableName == 'form_freight_estimate' || $tableName == 'form_freight_estimate' || $tableName == 'form_sample_materials_po' || $tableName == 'form_bulk_materials_po') {
            $tableName = 'form_purchase_order';
        }
        elseif ($tableName == 'form_sample_jobwork_po' || $tableName == 'form_bulk_jobwork_po') {
            $tableName = 'form_production_order';
        }
        $table = Helpers::snakeCaseToCamelCase($tableName);
        $modelName = 'Platform\Form\Models\\'.ucfirst($table);
        //dd($modelName);
        $formData = $this->collection($modelName::where('form_user_id','=', $formUser->id)->get(), new FormTransformer);
        $formData = $this->manager->createData($formData)->toArray();
        //dd($formUser->id);
        //dd($modelName::where('form_user_id','=', $formUser->id)->get());
        //dd($formData);
        $data = [
            'formUserId' => $formUser->id,
            'meta'=> $formData['data'] ,
            'formId' => \Platform\Form\Models\Forms::where('id', '=' , $formUser->form_name_id)->pluck('id'),
            'formName' => \Platform\Form\Models\Forms::where('id', '=' , $formUser->form_name_id)->pluck('form_name'),
            'formStatus' => \Platform\Form\Models\FormStatus::where('id', '=' , $formUser->form_status_id)->pluck('status'),
            'formType' => \Platform\Form\Models\Forms::where('id', '=' , $formUser->form_name_id)->pluck('form_type'),
            'formTypeCode' => \Platform\Form\Models\Forms::where('id', '=' , $formUser->form_name_id)->pluck('form_table_name'),
            'formStatus' => \Platform\Form\Models\FormStatus::where('id', '=' ,$formUser->form_status_id)->pluck('status'),
           'createdBy' => $creator['data'] ,
           'updatedBy' => (isset($updator['data']))?$updator['data']:null ,
           'submittedBy' => (isset($submittor['data']))?$submittor['data']:null ,
           'submittedAt' => (isset($formUser->submitted_at))?$formUser->submitted_at:null ,
           'approvalRequestor' => (isset($approval_requestor['data']))?$approval_requestor['data']:null,
           'isApproved' => $formUser->is_approved ,
           'approvedBy' => (isset($approver['data']))?$approver['data']:null ,
           'approvedAt' => (isset($formUser->approved_at))?$formUser->approved_at:null ,
           'rejectedBy' => (isset($rejector['data']))?$rejector['data']:null ,
           'remark' => (isset($formUser->remark))?$formUser->remark:null ,
           'createdAt' => $formUser->created_at,
            'updatedAt' => $formUser->updated_at

        ];

        return $data;
    }
}
