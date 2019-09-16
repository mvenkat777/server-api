<?php

namespace Platform\Users\Commands;

class UserDetailsCommand
{

    public $userId;
    public $firstName;
    public $lastName;
    public $country;
    public $state;
    public $city;
    public $mobileNumber;
    public $location;

    function __construct($data, $id)
    {
        $this->userId = $id;
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->country =$data['country'];
        $this->state =$data['state'];
        $this->city =$data['city'];
        $this->mobileNumber = isset($data['mobileNumber']) ? $data['mobileNumber'] : null;
        $this->location = isset($data['location']) ? $data['location'] : null;
    }
}
