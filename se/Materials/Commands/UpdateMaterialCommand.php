<?php  

namespace Platform\Materials\Commands;

class UpdateMaterialCommand {

	/**
	 * @var string
	 */
	public $id;

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
 

    function __construct($data , $id)
    {
        $this->id = $id;

        $this->materialType =  trim($data['materialType']);
    	
    	$this->construction =  trim($data['construction']);
    	$this->constructionType =  trim($data['constructionType']);

    	$this->fabricType =  trim($data['fabricType']);
    	$this->fiber1 =  trim($data['fiber1']);
    	$this->fiber1Percentage =  trim($data['fiber1Percentage']);
    	$this->fiber2 =  trim($data['fiber2']);
    	$this->fiber2Percentage =  trim($data['fiber2Percentage']);
    	$this->fiber3 =  trim($data['fiber3']);
    	$this->fiber3Percentage =  trim($data['fiber3Percentage']);
    	$this->otherFibers =  $data['otherFibers'];
    	
    	$this->weight =  trim($data['weight']);
    	$this->weightUOM =  trim($data['weightUOM']);
    	$this->cuttableWidth =  trim($data['cuttableWidth']);
    	$this->widthUOM =  trim($data['widthUOM']);
             
    }


}