<?php  

namespace Platform\Apps\Commands;

class CreateAppCommand {

    public $app_name;
    public $token;
 

    function __construct($data)
    {
        $this->app_name = $data['app_name'];
        $this->token = $data['token'];
       
    }


}