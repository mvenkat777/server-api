<?php

namespace Platform\Boards\Commands;

class CreateBoardFromAdminPanelCommand
{
	/**
	 * @var array
	 */
	public $data;

	public function __construct($data){
		$this->data = $data;
	}
}
