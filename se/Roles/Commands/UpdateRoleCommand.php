<?php  

namespace Platform\Roles\Commands;

class UpdateRoleCommand {

    public $name;
    public $description;
    public $groupId;
    public $appPermission;
    public $id;
 

    function __construct($data , $id)
    {
        $this->name = trim($data['name']);
        $this->description = trim($data['description']);
        $this->groupId = $data['groupId'];
        $this->appPermission = $data['appPermission'];
        $this->id = $id;
             
    }


}