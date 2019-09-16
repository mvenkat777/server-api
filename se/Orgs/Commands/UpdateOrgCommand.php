<?php  

namespace Platform\Orgs\Commands;

class UpdateOrgCommand {

    public $name;
    public $url;
    public $description;
    public $logo;
    public $id;
 

    function __construct($data , $id)
    {
        $this->name = $data['name'];
        $this->url = $data['url'];
        $this->description = $data['description'];
        $this->logo = $data['logo'];
        $this->id = $id;
             
    }


}