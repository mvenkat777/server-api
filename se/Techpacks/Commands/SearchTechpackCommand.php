<?php

namespace Platform\Techpacks\Commands;

class SearchTechpackCommand
{
	public $search;
	public $item;

	public function __construct($search, $item)
	{
		$this->search = $search;
		$this->item = $item;
	}


}
