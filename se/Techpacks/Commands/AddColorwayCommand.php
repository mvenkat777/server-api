<?php

namespace Platform\Techpacks\Commands;

class AddColorwayCommand
{
	public $techpackId;
	public $data;

	public function __construct($techpackId, $data) {
		$this->techpackId = $techpackId;
		$this->data = $data;
	}	
}
		
		
