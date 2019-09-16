<?php

namespace Platform\Techpacks\Commands;

class ShareTechpackCommand
{
	/**
	 * @var string
	 */
	public $techpackId;

	/**
	 * @var array
	 */
	public $users;

	/**
	 * @param string $techpackId
	 * @param array $data
	 */
	public function __construct($techpackId, $users)
	{
		$this->techpackId = $techpackId;
		$this->users = $users;
	}


}
