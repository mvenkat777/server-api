<?php

namespace Platform\Form\Transformers;

use Platform\Form\Models\Forms;
use Platform\Form\Models\FormUser;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\App\Helpers\Helpers;
use Platform\Form\Models\FormStatus;

class FormTransformer extends TransformerAbstract
{
    public function transform($form)
    {
        $collect = [];
        $specialCase = ['created_by', 'submitted_by', 'approved_by', 'rejected_by'];
        $dataCase = ['data','se_issuing_office', 'vendor', 'customer_id', 'sizes'];
        $formUserStatus = FormUser::where('id',$form->form_user_id)->first()->form_status_id;
        if($formUserStatus == 3){
            $formUserStatus = 1;
        }
        $formArray = $form->toArray();
        if(isset($formArray['form_user_id'])){
                $formUser = \Platform\Form\Models\FormUser::where('id', $form->toArray()['form_user_id'])->where('is_approved', true)->first();
                if($formUser)
                {
                    $required = [
                        'created_by' => $formUser->created_by,
                        'created_at' => $formUser->created_at,
                        'is_approved' => $formUser->is_approved,
                        'approved_at' => $formUser->approved_at,
                        'approved_by' => $formUser->approved_by
                    ];
                $formArray = array_merge($formArray, $required);
                }
            }
        foreach ($formArray as $key => $value) {
            if(in_array($key, $specialCase) && isset($formArray[$key]))
            {
                $value = $this->getUser($value);
            }
            if(in_array($key, $dataCase) && isset($form->toArray()[$key]))
            {
                $value = json_decode($value);
            }
            $collect[Helpers::snakeCaseToCamelCase($key)] = $value;

        }
        $collect['formStatus'] = FormStatus::where('id', $formUserStatus )->first()->status;
        return $collect;
    }

    public function getUser($id)
    {
        $user = \App\User::find($id);
        return [
            'id' => $user->id,
            'displayName' => $user->display_name,
            'email' => $user->email,
            'lastLoginLocation' => []
        ];
    }
}
