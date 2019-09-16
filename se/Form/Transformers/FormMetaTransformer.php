<?php

namespace Platform\Form\Transformers;

use Platform\Form\Models\Forms;
use League\Fractal\TransformerAbstract;
use Platform\App\Helpers\Helpers;

class FormMetaTransformer extends TransformerAbstract
{
    public function transform(Forms $form)
    {
        return [
            'id' => $form->id,
            'formName' => $form->form_name,
            'formTableName' => $form->form_table_name,
            'formType' => $form->form_type,
            'formDescription' => $form->form_description,
            'formImage' => json_decode($form->form_image)
        ];
    }
}
