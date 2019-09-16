<?php

namespace Platform\Techpacks\Commands;

class AssociateTechpackCommand
{
    public $id;
    public $action;
    public $user_id;
    public $permission;

    public function __construct($id, $action, $user_id, $permission)
    {
        $this->id = $id;
        $this->action = $action;
        $this->user_id = $user_id;
        $this->permission = $permission;
    }
}
