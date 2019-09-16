<?php  

namespace Platform\Orgs\Commands;

class CreateOrgCommand {

    public $name;
    public $url;
    public $description;
    public $logo;
 

    function __construct($data)
    {
        $this->name = $data['name'];
        $this->url = $data['url'];
        $this->description = $data['description'];
        $this->logo = $data['logo'];
       
    }


}