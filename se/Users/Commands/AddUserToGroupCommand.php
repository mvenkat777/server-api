<?php 

namespace Platform\Users\Commands;

class AddUserToGroupCommand 
{
	
	/**
	 * @var string
	 */
    public $groupName;

    /**
     * @var string
     */
    public $email;

     /**
     * @var string
     */
    public $permission;

    

    /**
     * @param array $data
     */
    function __construct($data)
    {
        $this->groupName = $data['groupName'];
        $this->email = $data['email'];
        $this->permission = $data['permission'];
        
    }
}