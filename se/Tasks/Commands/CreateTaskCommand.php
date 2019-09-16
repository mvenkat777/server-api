<?php

namespace Platform\Tasks\Commands;

use Carbon\Carbon;

class CreateTaskCommand
{
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var date
	 */
	public $dueDate;

	/**
	 * @var string
	 */
	public $priorityId;

	/**
	 * @var array
	 */
	public $tags;

	/**
	 * @var string
	 */
	public $category;

	/**
	 * @var string
	 */
	public $assignee;

	/**
	 * Row number when uploading tasks
	 * @var integer
	 */
	public $rowNumber;

	/**
	 * To check task is creating through upload
	 * @var boolean
	 */
	public $byUpload;

	/**
	 * Task creator id (used for TNA item dispatch)
	 * @var string/UUID of user
	 */
	public $creatorId;

	/**
	 * @var string/UUID od TNAItem
	 */
	public $tnaItemId;

	/**
	 * @param array $data
	 */
	function __construct($data)
	{
		$this->creatorId = isset($data['creatorId']) ? $data['creatorId'] : \Auth::user()->id;
		$this->title = isset($data['title']) ? $data['title'] : NULL;
		$this->description = isset($data['description']) ? $data['description'] : NULL;
		$this->dueDate = Carbon::parse($data['dueDate']);
		$this->priorityId = isset($data['priorityId']) ? $data['priorityId'] : '1';
		$this->tags = isset($data['tags']) ? $data['tags'] : NULL;
		$this->category = $data['category'];
		$this->assignee = isset($data['assignee']) ? $data['assignee'] : NULL;
		$this->rowNumber = isset($data['row']) ? $data['row'] : NULL;
		$this->byUpload = isset($data['byUpload']) ? $data['byUpload'] : false;
		$this->tnaItemId = isset($data['tnaItemId']) ? $data['tnaItemId'] : NULL;
        $this->skipCheck = isset($data['skipCheck']) ? $data['skipCheck'] : false;
	}
}
