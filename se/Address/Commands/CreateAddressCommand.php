<?php

namespace Platform\Address\Commands;

use Platform\App\Exceptions\SeException;

class CreateAddressCommand 
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $line1;

    /**
     * @var string
     */
    public $line2;

    /**
     * @var string
     */
    public $city;

    /**
     * @param string
     */
    public $state;

    /**
     * @param string
     */
    public $zip;

    /**
     * @param string
     */
    public $country;

    /**
     * @param integer
     */
    public $phone;

    /**
     * @param boolean
     */
    public $isPrimary;

    function __construct($data)
    {
        $this->label = isset($data['label']) ? $data['label']:null;
        $this->line1 = isset($data['line1']) ? $data['line1'] : null;
        $this->line2 = isset($data['line2']) ? $data['line2'] : null;
        $this->city = isset($data['city']) ? $data['city']:null;
        $this->state = isset($data['state']) ? $data['state']:null;
        $this->zip = (isset($data['zip']) && is_numeric($data['zip'])) ? $data['zip'] : null;
        $this->country = isset($data['country']) ? $data['country']:null;
        $this->phone = isset($data['phone']) ? $data['phone']:null;
        $this->isPrimary = (isset($data['isPrimary']) && !($data['isPrimary'] == "")) ? $data['isPrimary']:false;
        $this->airCargoPort = isset($data['airCargoPort']) ? $data['airCargoPort'] : null;
        $this->seaCargoPort = isset($data['seaCargoPort']) ? $data['seaCargoPort'] : null;
    }
}