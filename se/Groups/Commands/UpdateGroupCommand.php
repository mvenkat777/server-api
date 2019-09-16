<?php  

namespace Platform\Groups\Commands;

class UpdateGroupCommand {

    public $name;
    public $description;
    public $id;
 

    function __construct($data , $id)
    {
        $this->name = trim($data['name']);
        $this->description = trim($data['description']);
        $this->id = $id;
             
    }


}