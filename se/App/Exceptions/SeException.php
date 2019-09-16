<?php

namespace Platform\App\Exceptions;

class SeException extends \Exception
{
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var integer
	 */
	protected $httpStatusCode;

	/**
	 * @var string
	 */
	protected $seStatusCode;

	/**
	 * @param string $message
	 * @param integer $httpStatusCode
	 * @param string $seStatusCode
	 */
	public function __construct($message, $httpStatusCode, $seStatusCode = NULL){
		$this->message = $message;
		$this->httpStatusCode = $httpStatusCode;
		$this->seStatusCode = is_null($seStatusCode) ? 'SE_'.$httpStatusCode : 'SE_'.$seStatusCode;
	}

	/**
	 * Get Sourceeasy custom exception message
	 * @return string
	 */
	public function getSeMessage(){
		return $this->message;
	}

	/**
	 * Get HTTP status code
	 * @return integer [description]
	 */
	public function getHttpStatusCode(){
		return $this->httpStatusCode;
	}

	/**
	 * Get Sourceeasy custom status code
	 * @return string
	 */
	public function getSeStatusCode(){
		return $this->seStatusCode;
	}

}
