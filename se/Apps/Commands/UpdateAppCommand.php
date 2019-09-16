<?php  

namespace Platform\Apps\Commands;

class UpdateAppCommand {

    public $app_name;
    public $id;
 

    function __construct($data , $id)
    {
        $this->app_name = $data['app_name'];
        $this->id = $id;
             
    }


}