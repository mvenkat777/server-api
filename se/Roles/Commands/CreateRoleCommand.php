<?php  

namespace Platform\Roles\Commands;

class CreateRoleCommand {

    public $name;
    public $description;
    public $groupId;
    public $appPermission;
 

    function __construct($data)
    {
        $this->name = trim($data['name']);
        $this->description = trim($data['description']);
        $this->groupId = $data['groupId'];
        $this->appPermission = $data['appPermission'];
       
    }


}