<?php
namespace Platform\Tasks\Commands;

class UploadTasksCommand 
{
	/**
	 * CSV uploaded by user
	 * 
	 * @var file
	 */
	public $taskFile;

	/**
	 * @param array $data
	 */
	public function __construct($data){
		$this->taskFile = $data['taskFile'];
	}

}