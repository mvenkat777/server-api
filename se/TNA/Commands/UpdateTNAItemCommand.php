<?php

namespace Platform\TNA\Commands;

class UpdateTNAItemCommand 
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
	 * @var string (UUID of users)
	 */
	public $representorId;

	/**
	 * @var string UUID
	 */
	public $dependorId;

	/**
	 * @var array of UUID
	 */
	public $visibility;

	/**
	 * @var string UUID
	 */
	public $itemId;

	/**
	 * @var string UUID
	 */
	public $tnaId;

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
     * @var string
     */
    public $delta;

    /**
     * @var Object
     */
    public $tna;

    /*
     * @var Object
     */
    public $tnaItem;

    /**
     * @var $data array
     * @var $itemId string UUID
     * @var $tnaId string UUID
     * @var $tnaItem Object
     * @var $tna Object
     */
	public function __construct($data, $itemId, $tnaId, $tnaItem = null, $tna = null)
	{
		$this->title = $data['title'];
		$this->description = isset($data['description']) ? $data['description'] : NULL;
		$this->plannedDate = $data['plannedDate'];
		$this->isMilestone = $data['isMilestone'];
		$this->representorId = $data['representor']['id'];
		$this->itemId = $itemId;
		$this->tnaId = $tnaId;
		$this->visibility = count($data['visibility']) == 0 ? [1] : $data['visibility'];
		$this->dependorId = $this->checkDependorId($data) ? NULL : $data['dependor']['id'];
		//$this->isParallel = isset($data['isParallel']) ? $data['isParallel'] : false;
        $this->tnaItem = $tnaItem;
        $this->tna = $tna;
        //$this->projectedDate = $data['projectedDate'];
        //$this->taskDays  = $data['taskDays'];
        //$this->delta = $data['delta'];
	}

	private function checkDependorId($data)
	{
		return (!isset($data['dependor']) 
					|| is_null($data['dependor'])
					|| ($data['dependor'] == ""));
	}

}
