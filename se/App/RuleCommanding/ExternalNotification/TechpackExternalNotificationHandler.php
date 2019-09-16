<?php
namespace Platform\App\RuleCommanding\ExternalNotification;

use App\Role;
use App\Group;

/**
* For Sending email notification of email
*/
class TechpackExternalNotificationHandler extends Notifier
{
    protected $userTransformer = '\Platform\Users\Transformers\MetaUserTransformer';
    protected $customerTransformer = '\Platform\Customer\Transformers\MetaCustomerTransformer';

    public function updateTechpackStage($techpack, $actor)
    {
        $style = $techpack->style;
        if(!(is_null($style) || count($style))){
            return $style;
        }
        $techpack->link = $this->getHostUrl().'techpack/'.$techpack->id.'/home';
        $techpack->actorName = $actor->display_name;
        $subject = $actor->display_name.'('.$actor->email.') has changed techpack stage to : '.$techpack->stage;
        $sales_representative_id = 'sales_representative_id';
        $product_development_lead_id = 'product_development_lead_id';
        $this->processMail($techpack, $actor, [$style->line->$sales_representative_id, $style->line->$product_development_lead_id], 'emails.techpacks.UpdateTechpackStage', $subject);
        return $techpack;
    }

    public function processMail($techpack, $actor, $defaultReceiver, $view, $subject)
    {
        $isAlreadySent = [];
        foreach ($defaultReceiver as $key => $value) {
            if($value == $actor->email || $value == $actor->id){
                continue;
            }
            $receiver =  \App\User::where('email', $value)->orWhere('id', $value)->first();
            if(!in_array($receiver->email, $isAlreadySent)){
                $techpack->displayName = $receiver->display_name;
                $this->notifyViaEmail($techpack, $view, [$receiver->email], $subject);
                array_push($isAlreadySent, $receiver->email);
            }
        }
        return true;
    }

    protected function getGroupReceiver()
    {
        $group = Group::where('name', 'productteam')->first();
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


}