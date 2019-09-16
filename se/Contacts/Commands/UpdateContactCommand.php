<?php

namespace Platform\Contacts\Commands;

class UpdateContactCommand {

    /**
     * @var string
     */
    public $designation;

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
     * @var int
     */
    public $contactId;    

    /**
     * @var boolean
     */
    public $isPrimery;

   

    public function __construct($data)
    {
        $this->contactId = $data['contactId'];
        $this->label = isset($data['label'])?  $data['label'] : NULL;
        $this->designation = is_null($data['designation'])? NULL : $data['designation'];
        $this->email1 = isset($data['email1'])?  $data['email1'] : NULL;
        $this->email2 = isset($data['email2'])?  $data['email2'] : NULL;
        $this->mobileNumber1 = isset($data['mobileNumber1'])?  $data['mobileNumber1'] : NULL;
        $this->mobileNumber2 = isset($data['mobileNumber2'])?  $data['mobileNumber2'] : NULL;
        $this->mobileNumber3 = isset($data['mobileNumber3'])?  $data['mobileNumber3'] : NULL;
        $this->skypeId = isset($data['skypeId'])?  $data['skypeId'] : NULL;
        $this->isPrimery = isset($data['isPrimery'])?  $data['isPrimery'] : FALSE;
    }


} 