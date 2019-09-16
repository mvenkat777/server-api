<?php

namespace Platform\Uploads\Commands;

class DeleteFileCommand
{
	/**
	 * @var int
	 */
	public $fileId;

	/**
	 * @param int $id  
	 */
	function __construct($id)
	{
		$this->fileId = $id;
	}
}