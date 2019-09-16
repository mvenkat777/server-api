<?php

namespace Platform\Contacts\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\HashidsManager;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Contacts\Repositories\Contracts\ContactRepository;
use App\Contact;

class EloquentContactRepository extends Repository implements ContactRepository
{
    public function model()
    {
        return 'App\Contact';
    }
    /**
     * @param MakeNewPaymentCommand $command
     * @return mixed
     */
    
    public function makeContact(CreateContactCommand $command)
    {   
        $Contact = [
            'label' => $command->label,
            'designation' => $command->designation,
            'email1' => $command->email1,
            'email2' => $command->email2,
            'mobile_number1' => $command->mobileNumber1,
            'mobile_number2' => $command->mobileNumber2,
            'mobile_number3' => $command->mobileNumber3,
            'skype_id' => $command->skypeId,
            'is_primary' => $command->isPrimery       
        ];
        return $this->model->create($Contact); 
        
    }
    /**
     * @param showLinkContent $command
     * @return all
     */

    public function showContact($command)
    {
            return $this->model->where('id','=',$command->id)->first();
    }
    /**
     * @param getRequestedStatus $command
     * @return 1
     */
    public function getAllContacts($command)
    {
        return $this->model->paginate(100);
    }
    /**
     * @param getRequestedStatus $command
     * @return 1
     */

    public function updateContact($command)
    {
        $contactDetail = $this->model->where('id','=',$command->contactId)->first();
        if(is_null($contactDetail))
        {
            return 0;
        }
        $contact = [
            'label' =>is_null($command->label)? $contactDetail->label:$command->label,
            'email1' =>is_null($command->email1)? $contactDetail->email1:$command->email1,
            'email2' =>is_null($command->email2)? $contactDetail->email2:$command->email2,
            'mobile_number1' =>is_null($command->mobileNumber1)? $contactDetail->mobileNumber1:$command->mobileNumber1,
            'mobile_number2' =>is_null($command->mobileNumber2)? $contactDetail->mobileNumber2:$command->mobileNumber2,
            'mobile_number3' =>is_null($command->mobileNumber3)? $contactDetail->mobileNumber3:$command->mobileNumber3,
            'designation' => is_null($command->designation)? $contactDetail->designation:$command->designation,
            'skype_id' =>is_null($command->skypeId)? $contactDetail->skypeId:$command->skypeId,           
            'is_primary' => $command->isPrimery          
        ]; 
        return  $this->model->where('id','=',$command->contactId)->update($contact);
    }
    /**
     * @param deleteContact $command
     * @return 1
     */

    public function deleteContacts($command)
    {
        return $this->model->whereIn('id', $command)->delete();
    }

    public function showUserContact($command)
    {
        return $this->model->where('id','=',$command->contactId)->where('user_id','=',$command->userId)->first();
    }


}
