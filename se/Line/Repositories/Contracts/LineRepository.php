<?php

namespace Platform\Line\Repositories\Contracts;

interface LineRepository 
{
	/**
	 * Return model
	 *
	 * @return string
	 */
	public function model();

	/**
	 * Creates a new line entity
	 *
	 * @return mixed
	 */
	public function createNewLine($data);

	/**
	 * Creates a new line entity
	 *
	 * @return mixed
	 */
	public function updateLine($id, $data);
}
