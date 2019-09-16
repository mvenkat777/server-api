<?php

namespace Platform\Form\Transformers;

use Platform\Form\Models\FormHistory;
use Platform\Form\Models\Forms;
use Platform\Form\Models\FormStatus;
use League\Fractal\TransformerAbstract;
use mikemccabe\JsonPatch\JsonPatch;
use Platform\App\Helpers\Helpers;
use App\User;

class FormHistoryTransformer extends TransformerAbstract
{
    public function transform($collection)
    {
        $data = [];
        $diffData = [];
        foreach ($collection as $key => $history) {
            $formNameId = json_decode($history->his_form_user_olddata);
            $validate = Forms::where('id', $formNameId->form_name_id)->select('form_table_name')->first();
            if($validate->form_table_name == 'form_sample_invoice' || $validate->form_table_name == 'form_production_deposit_invoice' || $validate->form_table_name == 'form_production_shipment_invoice'){
            $table = Helpers::snakeCaseToCamelCase('form_sales_order');
            $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            } elseif ($validate->form_table_name == 'form_sample_po' || $validate->form_table_name == 'form_fabric_po' || $validate->form_table_name == 'form_production_po' || $validate->form_table_name == 'form_freight_estimate') {
                $table = Helpers::snakeCaseToCamelCase('form_purchase_order');
                $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            } else {
                $table = Helpers::snakeCaseToCamelCase($validate->form_table_name);
                $modelName = 'Platform\Form\Models\\'.ucfirst($table);
            }
            $formUserId = $modelName::where('id', $history->id)->first();
            if($validate->form_table_name == $history->type){
                if(!is_null($history->his_form_user_newdata)){
                    $old = json_decode($history->his_form_user_olddata);
                    $new = json_decode($history->his_form_user_newdata);
                } elseif(!is_null($history->his_form_newdata)){
                    $old = json_decode($history->his_form_olddata);
                    $new = json_decode($history->his_form_newdata);
                }
                if($old->id != $formUserId->form_user_id && $old->id !== $history->id){
                    continue;
                }
                
                $actorUser = is_null(json_decode($history->his_form_user_newdata)) ? json_decode($history->his_form_user_olddata) : json_decode($history->his_form_user_newdata);
                array_push($diffData, $this->getDifference($old, $new, $actorUser));
            }
        }
        if(count($diffData))
                array_push($data, call_user_func_array('array_merge', $diffData));
        if(count($data))
            return array_reverse($data[0]);
        return $data;
    }

    public function getDifference($old, $new, $actorUser = NULL)
    {
        $diff = [];
        $blackList = ['updated_at', 'is_rejected', 'is_approved', 'submitted_by', 'created_by', 'approved_by', 'rejected_by', 'updated_by'];
        foreach ($new as $key => $value) {
            if(in_array($key, $blackList))
                continue;
            if(is_object($value))
            {
                $data = [
                    'fieldName' => Helpers::snakeCaseToCamelCase($key),
                    'newValue' => 'updated',
                    'oldValue' => NULL
                ];
            } else {
                if($value != $old->$key){
                    if(!isset($actorUser->updated_by)){
                        $actorUser->updated_by = $actorUser->created_by;
                    }
                    $actor = $this->userFormat(User::find($actorUser->updated_by));
                    if($key == 'form_status_id'){
                        $value = FormStatus::where('id', $value)->first()->status;
                        $old->$key = FormStatus::where('id', $old->$key)->first()->status;
                    }
                    // if($key == 'submitted_by' || $key == 'created_by' || $key == 'approved_by' || $key == 'rejected_by' || $key == 'updated_by'){
                    //     if(!is_null($value)){
                    //         $actor = $this->userFormat(User::find($value));
                    //         $value = $this->userFormat(User::find($value));
                    //     }
                    //     if(!is_null($old->$key)){
                    //         $actor = $this->userFormat(User::find($old->$key));
                    //         $old->$key = $this->userFormat(User::find($old->$key));
                    //     }
                    // }
                     if($key == 'submitted_at' || $key == 'created_at' || $key == 'approved_at' || $key == 'rejected_at'){
                        $new->updated_at = $value;
                    }
                    if($key == 'data')
                        continue;
                    $data = [
                        'fieldName' =>  array_key_exists($key, $this->transformName()) ? $this->transformName()[$key] : Helpers::snakeCaseToCamelCase($key),
                        'newValue' => $value,
                        'oldValue' => $old->$key,
                        'updatedAt' => $new->updated_at,
                        'actor' => $actor
                    ];
                    if($key == 'submitted_at' || $key == 'created_at' || $key == 'approved_at' || $key == 'rejected_at'){
                        $data['isObjectType'] = true;
                    }
                    array_push($diff, $data);
                }
            }
        }
        return $diff;
    }

    public function userFormat($user)
    {
        return [
            'id' => $user->id,
            'displayName' => $user->display_name,
            'email' => $user->email
        ];
    }

    public function transformName()
    {
        return [
            'submitted_at' => 'submitted',
            'created_at' => 'created',
            'approved_at' => 'approved',
            'rejected_at' => 'rejected',
            'updated_at' => 'updated_at',
            'form_status_id' => 'form status',
            'cancel_date' => 'cancel-date',
            'delivery_date' => 'delivery-date',
            'order_date' => 'order-date',
            'bill_to_address' => 'bill-To-Address'
        ];
    }
}
