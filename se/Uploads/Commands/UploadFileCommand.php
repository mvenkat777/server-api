<?php

namespace Platform\Uploads\Commands;

class UploadFileCommand
{
	/**
	 * @var string
	 */
	public $isPublic;

	/**
	 * @var text
	 */
	public $description;

	/**
	 * @var string
	 */
	public $bucket;

	/**
	 * @var string
	 */
	public $folder;

	/**
	 * @var array
	 */
	public $files;

	/**
	 * @param array $data  
	 */
	function __construct($data)
	{
		$this->files = $data['files'];
		$this->description = isset($data['description'])? $data['description'] : null;
		$this->isPublic = isset($data['isPublic'])? $data['isPublic'] : true;
		$this->bucket = isset($data['bucket'])? $data['bucket'] : null;
		$this->folder = isset($data['folder'])? $data['folder'] : null;
	}
}