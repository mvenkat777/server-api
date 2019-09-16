<?php

namespace Platform\Address\Commands;

class UpdateAddressCommand {

	/**
     * @var integer
    */
    public $id;
    /**
	 * @var string
	 */
    public $userId;

    /**
     * @var string
     */
    public $userAddressLabel;

    /**
     * @var string
     */
    public $userAddressLine1;

    /**
     * @var string
     */
    public $userAddressLine2;

    /**
     * @var string
     */
    public $userAddressCity;

    /**
     * @param string
     */
    public $userAddressState;

    /**
     * @param string
     */
    public $userAddressZip;

    /**
     * @param string
     */
    public $userAddressCountry;

    /**
     * @param integer
     */
    public $userAddressPhone;

    function __construct($data)
    {
        $this->id = $data['id'];
        $this->label = $data['label'];
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