<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

/**
* For Sending email notification of email
*/
class StyleExternalNotificationHandler extends Notifier
{
    protected $userTransformer = '\Platform\Users\Transformers\MetaUserTransformer';
    protected $customerTransformer = '\Platform\Customer\Transformers\MetaCustomerTransformer';

    public function createNewStyle($style, $actor)
    {   
       
        $valDefinition = $this->updateCollection($style);
        $valDefinition->link = $this->getHostUrl().'line/'.$valDefinition->line->id;
        $valDefinition->actorName = $actor->display_name;
        $valDefinition->actorEmail =$actor->email;
        $subject = $actor->display_name.'('.$actor->email.') has added a new style to the line : '.$valDefinition->line->name;
        $defaultReceiver = ['sales_representative_id', 'product_development_lead_id'];
        $this->processMail($valDefinition, $actor, $defaultReceiver, 'emails.style.CreateNewStyle', $subject);
        return $style;
    }

    public function productBriefReviewApproved($style, $actor)
    {
        $valDefinition = $this->updateCollection($style);
        $getNotifier = $this->validateLineApproval($valDefinition, $actor);
        if(!$getNotifier){
            return $style;
        }
        $defaultReceiver = $getNotifier;
        $valDefinition->link = $this->getHostUrl().'line/'.$valDefinition->line->id;
        $valDefinition->actorName = $actor->display_name;
        $subject = $actor->display_name.'('.$actor->email.') has reviewed the Product Brief : '.$valDefinition->review[$style->command->approvalNameId - 1]->name;
        $this->processMail($valDefinition, $actor, $defaultReceiver, 'emails.style.ReviewApproved', $subject);
        return $style;
    }

    public function archiveStyle($style, $actor)
    {
        $valDefinition = $this->updateCollection($style);
        $valDefinition->link = $this->getHostUrl().'line/'.$valDefinition->line->id;
        $valDefinition->actorName = $actor->display_name;
        $valDefinition->actorEmail =$actor->email;
        $subject = $actor->display_name.'('.$actor->email.') has archived a style from the line : '.$valDefinition->line->name;
        $defaultReceiver = ['sales_representative_id', 'product_development_lead_id'];
        $this->processMail($valDefinition, $actor, $defaultReceiver, 'emails.style.ArchiveStyle', $subject);
        return $style;
    }

    public function validateLineApproval($valDefinition, $actor)
    {
        $sales_representative_id = 'sales_representative_id';
        $sales = $valDefinition->line->$sales_representative_id;
        $product_development_lead_id = 'product_development_lead_id';
        $pdLead = $valDefinition->line->$product_development_lead_id;
        
        if($actor->id == $sales['id']){
            return ['product_development_lead_id'];
        }elseif($actor->id == $pdLead['id']){
            return ['production_lead_id'];
        } else {
            return ['product_development_lead_id', 'production_lead_id'];
        }
    }

    public function processMail($valDefinition, $actor, $defaultReceiver, $view, $subject)
    {
        $isAlreadySent = [];
        foreach ($defaultReceiver as $key => $value) {
            $check = $valDefinition->line->$value;
            if($check['email'] != $actor->email && !in_array($check['email'], $isAlreadySent)){
                array_push($isAlreadySent, $check['email']);
                $valDefinition->displayName = $check['displayName'];
                $this->notifyViaEmail($valDefinition, $view, [$check['email']], $subject);
            }
        }
        return true;
    }

    public function updateCollection($data)
    {
        if(isset($data->line->customer_id)){
            $modelObject = $this->find($data->line->customer_id, '\App\Customer');
            if(!is_null($modelObject))
                $data->line['customer_id'] = (new $this->customerTransformer)->transform($modelObject);
        }
        if(isset($data->line->sales_representative_id)){
            $modelObject = $this->find($data->line->sales_representative_id, '\App\User');
            if(!is_null($modelObject))
                $data->line['sales_representative_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->line->production_lead_id)){
            $modelObject = $this->find($data->line->production_lead_id, '\App\User');
            if(!is_null($modelObject))
                $data->line['production_lead_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->line->merchandiser_id)){
            $modelObject = $this->find($data->line->merchandiser_id, '\App\User');
            if(!is_null($modelObject))
                $data->line['merchandiser_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        if(isset($data->line->product_development_lead_id)){
            $modelObject = $this->find($data->line->product_development_lead_id, '\App\User');
            if(!is_null($modelObject))
                $data->line['product_development_lead_id'] = (new $this->userTransformer)->transform($modelObject);
        }
        return $data;
    }

    public function find($id, $modelName)
    {
        return $modelName::find($id);
    }
}