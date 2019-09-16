<?php

namespace Platform\Payment\Commands;

class MakeNewPaymentCommand {

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $productName;

    /**
     * @var string
     */
    public $description;

    /**
     * @param string
     */
    public $amount;

    /**
     * @param text
     */
    public $uploadLinkObject;

    function __construct($data)
    {
        $this->email = $data['email'];
        $this->name = isset($data['name']) ? $data['name']:null;
        $this->productName = $data['productName'];
        $this->description = isset($data['description']) ? $data['description']:null;
        $this->amount = $data['amount'];
        $this->uploadLinkObject = isset($data['uploadLinkObject']) ? $data['uploadLinkObject'] : null;
    }


}