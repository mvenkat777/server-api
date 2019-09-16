<?php

namespace Platform\Dashboard\Commands;

class GetActivityByScopeCommand 
{
    public $scope;

    public $items;
    
    public $type;

	public function __construct($scope, $items = 20, $type){
        $this->scope = $scope;
        $this->items = (integer)$items;
        $this->type = $type;
	}

}
