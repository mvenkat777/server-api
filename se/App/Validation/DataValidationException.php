<?php

namespace Platform\App\Validation;

use Illuminate\Support\MessageBag;

class DataValidationException extends \Exception
{
    /**
     * @var MessageBag
     */
    private $errors;

    /**
     * @param $message
     * @param MessageBag $errors
     */
    public function __construct($message, MessageBag $errors)
    {
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
    	return $this->errors;
    }
}
