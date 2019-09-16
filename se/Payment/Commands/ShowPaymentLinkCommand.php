<?php

namespace Platform\Payment\Commands;

class ShowPaymentLinkCommand {

	/**
	 * @var string
	 */
    public $link;
    
    /**
	 * @var integer
	 */
    public $userLocation;

    function __construct($link,$userLocation)
    {
        $this->link = 'http://payments.sourceeasy.com/?paymentId='.$link;
        $this->userLocation = $userLocation;
    }


}