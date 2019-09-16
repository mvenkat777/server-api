<?php 

namespace Platform\Techpacks\Commands;

class GetTechpackCommentsCommand
{
	public $techpackId;

	public function __construct($techpackId)
	{
		$this->techpackId = $techpackId;
	}
}
