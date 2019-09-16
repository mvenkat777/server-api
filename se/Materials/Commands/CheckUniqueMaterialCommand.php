<?php

namespace Platform\Materials\Commands;


class CheckUniqueMaterialCommand
{

	/**
	 * @var string
	 */
	public $materialId;
	
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
	 * @var string
	 */
	public $fiber2;

	/**
	 * @var string
	 */
	public $fiber3;
	
	/**
	 * @var integer
	 */
	public $weight;

	
	function __construct($data)
    {	
    	$this->materialId =  isset($data['materialId'])?$data['materialId']:'';
    	$this->materialType =  $data['materialType'];
    	
    	$this->construction =  $data['construction'];
    	$this->constructionType = $data['constructionType'];

    	$this->fabricType =  $data['fabricType'];
    	$this->fiber1 =  $data['fiber1'];
    	$this->fiber2 =  $data['fiber2'];
    	$this->fiber3 =  $data['fiber3'];

    	$this->weight =  $data['weight'];
    	
    } 
}