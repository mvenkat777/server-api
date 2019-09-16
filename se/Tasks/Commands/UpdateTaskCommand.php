<?php

namespace Platform\Tasks\Commands;

class UpdateTaskCommand
{
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var text
	 */
	public $description;

	/**
	 * @var date
	 */
	public $dueDate;

	/**
	 * @var string enum
	 */
	public $priority;

	/**
	 * @var array
	 */
	public $tags;

	/**
	 * @var string
	 */
	public $categories;

	/**
	 * @var email
	 */
	public $assignee;

	// public $attachmentType;

	// public $attachmentData;

	/**
	 * @var array
	 */
	public $attachments;
	
	/**
	 * Task id
	 * @var string
	 */
	public $id;

	function __construct($data, $id)
	{
		$this->id = $id;
		$this->title = $data['title'];
		$this->description = $data['description'];
		$this->dueDate = $data['dueDate'];
		$this->priority = $data['priority']['PriorityId'];
		$this->tags = $data['tags'];
		$this->category = $data['categories'][0]['title'];
		$this->assignee = $data['assignee']['id'];
		$this->attachments = $data['attachments'];
	}

}