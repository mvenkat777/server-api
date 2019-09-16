<?php

namespace Platform\Materials\Commands;


class CreateMaterialCommand
{
	/**
	 * @var string
	 */
    public $materialReferenceNo;

    /**
	 * @var string
	 */
	public $materialType;
	
	/**
	 * @var string
	 */
	public $construction;

	/**
	 * @var string
	 */
	public $constructionType;
	
	/**
	 * @var string
	 */
	public $fabricType;
	
	/**
	 * @var string
	 */
	public $fiber1;

	/**
	 * @var integer
	 */
	public $fiber1Percentage;

	/**
	 * @var string
	 */
	public $fiber2;

	/**
	 * @var integer
	 */
	public $fiber2Percentage;

	/**
	 * @var string
	 */
	public $fiber3;

	/**
	 * @var integer
	 */
	public $fiber3Percentage;

	/**
	 * @var json
	 */
	public $otherFibers;
	
	/**
	 * @var integer
	 */
	public $weight;

	/**
	 * @var string
	 */
	public $weightUOM;

	/**
	 * @var integer
	 */
	public $cuttableWidth;

	/**
	 * @var string
	 */
	public $widthUOM;

	
	function __construct($data)
    {	
    	//$this->material_reference_no = $data['material_reference_no'];
    	$this->materialType =  !isset($data['materialType'])? NULL : $data['materialType'];
    	
    	$this->construction =  !isset($data['construction'])? NULL : $data['construction'];
    	$this->constructionType =  !isset($data['constructionType'])? NULL : $data['constructionType'];

    	$this->fabricType =  !isset($data['fabricType'])? NULL : $data['fabricType'];
    	$this->fiber1 =  !isset($data['fiber1'])? NULL : $data['fiber1'];
    	$this->fiber1Percentage =  !isset($data['fiber1Percentage'])? NULL : $data['fiber1Percentage'];
    	$this->fiber2 =  !isset($data['fiber2'])? NULL : $data['fiber2'];
    	$this->fiber2Percentage =  !isset($data['fiber2Percentage'])? NULL : $data['fiber2Percentage'];
    	$this->fiber3 =  !isset($data['fiber3'])? NULL : $data['fiber3'];
    	$this->fiber3Percentage =  !isset($data['fiber3Percentage'])? NULL : $data['fiber3Percentage'];
    	$this->otherFibers =  !isset($data['otherFibers'])? NULL : $data['otherFibers'];
    	
    	$this->weight =  !isset($data['weight'])? NULL : $data['weight'];
    	$this->weightUOM =  !isset($data['weightUOM'])? NULL : $data['weightUOM'];
    	$this->cuttableWidth =  !isset($data['cuttableWidth'])? NULL : $data['cuttableWidth'];
    	$this->widthUOM =  !isset($data['widthUOM'])? NULL : $data['widthUOM'];
    	
    } 
}