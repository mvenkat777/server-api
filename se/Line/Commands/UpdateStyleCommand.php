<?php

namespace Platform\Line\Commands;

class UpdateStyleCommand 
{
	/**
	 * @var string
	 */
	public $lineId;

	/**
	 * @var string
	 */
	public $styleId;

	/**
	 * @var array
	 */
	public $data;

	/**
	 * @param string $lineId
	 * @param string $styleId
	 * @param array $data
	 */
	public function __construct($lineId, $styleId, $data) {
		$this->lineId = $lineId;
		$this->styleId = $styleId;
		$this->data = $data;
	}

}
