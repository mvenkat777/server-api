<?php

namespace Platform\Users\Repositories\Contracts;

interface UserRepository
{
    public function model();

    /**
     * Get both owned and collaborated boards of a user
     *
     * @param  string $userId
     */
    public function getBoards($userId);

    /**
     * Get both owned and collaborated boards with picks of a user
     *
     * @param  string $userId
     */
    public function getBoardsWithPicks($userId);

    /**
     * Get owned boards sof the user by userId
     *
     * @param  string $userId
     */
	public function getOwnedBoards($userId);

    /**
     * Get boards on which the user is a collaborator
     *
     * @param  string $userId
     */
	public function getCollaboratedBoards($userId);
}
