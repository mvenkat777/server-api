<?php 

namespace Platform\Techpacks\Commands;

class AddTechpackCommentCommand
{
	public $techpackId;
	public $userId;
	public $parentId;
	public $comment;
	public $file;

	public function __construct($data, $techpackId)
	{
		$this->techpackId = $techpackId;
		$this->parentId = $data->parentId ? $data->parentId : null; 
		$this->userId = $data->userId;
		$this->comment = $data->comment;
		$this->file = $data->file;
	}


}
