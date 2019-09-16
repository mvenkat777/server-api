<?php

namespace Platform\TNA\Commands;

use Platform\App\Helpers\Helpers;

class CreateTNAItemCommand 
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
	 * @var integer
	 */
	public $taskDays;

	/**
	 * @var string DATE
	 */
	public $plannedDate;

	/**
	 * @var boolean
	 */
	public $isMilestone;

	/**
	 * @var string 
	 */
	public $representor;

	/**
	 * @var string UUID
	 */
	public $tnaId;

	/**
	 * @var string UUID
	 */
	public $dependorId;

	/**
	 * @var array of UUID
	 */
	public $visibility;

	/**
	 * @var boolean
	 */
	public $doSync;

	/**
	 * @var boolean
	 */
    public $skipCheck;

	/**
	 * @var integer
	 */
	public $departmentId;

    /**
     * @var boolean
     */
    public $isParallel;

    /**
     * @var string
     */
    public $label;

    /**
     * @var DATE
     */
    public $projectedDate;
    
    /**
     * @var $tna Object
     */
    public $tna;

    /**
     * @var string
     */
    public $creatorId;

    /**
     * @var string
     */
    public $templateId;

    /**
     * @var $data array
     * @var $tnaId string
     * @var $tna Model
     */
	public function __construct($data, $tnaId, $tna = null)
	{
		$this->title = $data['title'];
		$this->description = isset($data['description']) ? $data['description'] : NULL;
		//$this->taskDays = $data['taskDays'];
		$this->plannedDate = Helpers::isSetAndIsNotEmpty($data, 'plannedDate') ? $data['plannedDate'] : null;
		$this->isMilestone = $data['isMilestone'];
		$this->representor = $data['representor'];
		$this->tnaId = $tnaId;
		$this->visibility = !isset($data['visibility']) || (count($data['visibility']) == 0) ? [1] : $data['visibility'];
		$this->dependorId = $this->checkDependorId($data) ? null : $data['dependorId'];
		$this->departmentId = Helpers::isSetAndIsNotEmpty($data, 'departmentId') ? $data['departmentId'] : 1;
		$this->doSync = isset($data['doSync']) ? $data['doSync'] : true;
		//$this->skipCheck = isset($data['skipCheck']) ? $data['skipCheck'] : false;
		//$this->isParallel = isset($data['isParallel']) ? $data['isParallel'] : false;
        $this->tna = $tna;
        $this->creatorId = isset($data['creatorId']) && !empty($data['creatorId']) ? $data['creatorId'] : \Auth::user()->id;
        //$this->isPriorityTask = isset($data['isPriorityTask']) ? $data['isPriorityTask'] : false;
        $this->nodes = isset($data['nodes']) ? $data['nodes'] : [];
        $this->saveTemplate = isset($data['saveTemplate']) ? $data['saveTemplate'] : false;
        $this->templateId = isset($data['templateId']) ? $data['templateId'] : null;
	}

	private function checkDependorId($data)
	{
		return (!isset($data['dependorId']) 
					|| is_null($data['dependorId'])
					|| ($data['dependorId'] == ""));
	}

}
