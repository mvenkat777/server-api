<?php

namespace Platform\Line\Repositories\Contracts;

interface StyleRepository 
{
	public function model();
	
	/**
	 * Creates a new style in the line with line id $lineId
	 *
	 * @param string $lineId
	 * @return mixed
	 */
	public function createNewStyle($lineId);

	/**
	 * Update a style
	 *
	 * @param string $lineId
	 * @param string $styleId
	 * @param array $data
	 * @return mixed
	 */
	public function updateStyle($lineId, $styleId, $data); 

	/**
	 * Get a style based on lineId and styleId
	 *
	 * @param string $lineId
	 * @param string $styleId
	 * @return mixed
	 */
	public function getByLineIdAndStyleId($lineId, $styleId);
}
