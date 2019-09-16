<?php

namespace Platform\Contacts\Commands;



class CreateContactCommand
{

    /**
	 * @var string
	 */
	public $email;
	
	/**
	 * @var string
	 */
	public $label;
	
	/**
	 * @var string
	 */
	public $email1;
	
	/**
	 * @var string
	 */
	public $email2;
	
	/**
	 * @var string
	 */
	public $mobileNumber1;
	
	/**
	 * @var string
	 */
	public $mobileNumber2;

	/**
	 * @var string
	 */
	public $mobileNumber3;
	
	/**
	 * @var string
	 */
	public $skypeId;

	/**
	 * @var string
	 */
	public $designation;

	/**
	 * @var boolean
	 */
	public $isPrimery;
	
	function __construct($data)
    {	
        $this->email1 = !isset($data['email1'])? NULL : $data['email1'];
        $this->email2  = !isset($data['email2'])? NULL : $data['email2'];
        $this->mobileNumber1 = !isset($data['mobileNumber1'])? NULL : $data['mobileNumber1'];
        $this->mobileNumber2   = !isset($data['mobileNumber2'])? NULL : $data['mobileNumber2'];
        $this->mobileNumber3 = !isset($data['mobileNumber3'])? NULL : $data['mobileNumber3'];
        $this->skypeId    = !isset($data['skypeId'])? NULL : $data['skypeId'];
        $this->label = !isset($data['label'])? NULL : $data['label'];
        $this->isPrimery = !isset($data['isPrimery'])? FALSE : $data['isPrimery'];
        $this->designation = !isset($data['designation'])? NULL : $data['designation'];
    } 
}