<?php  

namespace Platform\Groups\Commands;

class CreateGroupCommand {

    public $name;
    public $description;
    public $token;
 

    function __construct($data)
    {
        $this->name = trim($data['name']);
        $this->description = trim($data['description']);
        $this->token = $data['token'];
       
    }


}