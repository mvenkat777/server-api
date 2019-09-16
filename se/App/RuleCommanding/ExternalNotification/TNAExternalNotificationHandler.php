<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

/**
* For Sending email notification of email
*/
class TNAExternalNotificationHandler extends Notifier
{
    protected $userTransformer = '\Platform\Users\Transformers\MetaUserTransformer';
    protected $customerTransformer = '\Platform\Customer\Transformers\MetaCustomerTransformer';

    public function tnaPublish($tna, $actor)
    {  
        $valDefinition = $this->updateCollection($tna->line());
        $valDefinition->calendarTitle = $tna->title;
        $valDefinition->link = $this->getHostUrl().'calendar/edit/'.$tna->id;
        $valDefinition->actorName = $actor->display_name;
        $valDefinition->po_target_date = $valDefinition->so_target_date->format('m-d-Y');
        $valDefinition->delivery_target_date_formatted = $valDefinition->delivery_target_date->format('m-d-Y');
        $subject = $actor->display_name.'('.$actor->email.') has published the calendar '.$tna->title. ' in the line '.$valDefinition->name;
        $defaultReceiver = ['sales_representative_id', 'product_development_lead_id'];
        $this->processMail($valDefinition, $actor, $defaultReceiver, 'emails.calendar.TNAPublish', $subject);
        return $tna;
    }

    public function processMail($valDefinition, $actor, $defaultReceiver, $view, $subject)
    {
        $isAlreadySent = [];
        foreach ($defaultReceiver as $key => $value) {
            $check = $valDefinition->$value;
            if($check['email'] != $actor->email && !in_array($check['email'], $isAlreadySent)){
                $valDefinition->displayName = $check['displayName'];
                $this->notifyViaEmail($valDefinition, $view, [$check['email']], $subject);
                array_push($isAlreadySent, $check['email']);
            }
        }
        return true;
    }

    public function updateCollection($data)
    {
        if(isset($data->customer_id)){
            $modelObject = $this->find($data->customer_id, '\App\Customer');
            if(!is_null($modelObject))
                $data['customer_id'] = (new $this->customerTransformer)->transform($modelObject);
        }
        if(isset($data->sales_representative_id)){
            $modelObject = $this->find($data->sales_representative_id, '\App\User');
            if(!is_null($modelObject))
                $data['sales_representative_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->production_lead_id)){
            $modelObject = $this->find($data->production_lead_id, '\App\User');
            if(!is_null($modelObject))
                $data['production_lead_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->merchandiser_id)){
            $modelObject = $this->find($data->merchandiser_id, '\App\User');
            if(!is_null($modelObject))
                $data['merchandiser_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->product_development_lead_id)){
            $modelObject = $this->find($data->product_development_lead_id, '\App\User');
            if(!is_null($modelObject))
                $data['product_development_lead_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        return $data;
    }

    public function find($id, $modelName)
    {
        return $modelName::find($id);
    }
}