<?php

namespace Platform\Contacts\Transformers;

use App\Http\Controllers\ApiController;
use App\Contact;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\UserTransformer;

class ContactTransformer extends TransformerAbstract
{
    public function transform(Contact $contact)
    {
        return [
            'contactId' => $contact->id,
            'label' => $contact->label,
            'email1' => $contact->email1,
            'email2' => $contact->email2,
            'mobileNumber1' => $contact->mobile_number1,
            'mobileNumber2' => $contact->mobile_number2,
            'mobileNumber3' => $contact->mobile_number3,
            'designation' => $contact->designation,
            'skypeId' => $contact->skype_id,        
            'isPrimery' => $contact->is_primery,        
            // 'createdAt' => $contact->created_at,
            // 'updatedAt' => $contact->updated_at
        ];
    }
}
