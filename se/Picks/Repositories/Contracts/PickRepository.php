<?php

namespace Platform\Picks\Repositories\Contracts;

interface PickRepository
{
	/**
	 * Return pick model
	 */
	public function model();

	/**
	 * Create a new pick
	 * @param  array $data
	 */
	public function createPick($data);

	/**
	 * Get pick by id
	 *
	 * @param  string $pickId
	 */
	public function getById($pickId);

	/**
	 * Check if authenticated  user it the owner of pick
	 *
	 * @param  string  $pickId
	 * @param  string  $userId
	 */
	public function isOwner($pickId, $userId);

	/**
	 * Delete a pick
	 *
	 * @param string $pickId
	 */
	public function deletePick($pickId);

	/**
	 * Update pick
	 *
	 * @param  string  $pickId
	 */
	public function updatePick($pickId, $data);
}
