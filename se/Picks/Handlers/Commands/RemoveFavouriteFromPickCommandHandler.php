<?php

namespace Platform\Picks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Picks\Repositories\Contracts\PickRepository;

class RemoveFavouriteFromPickCommandHandler implements CommandHandler 
{
    /**
     * @var PickRepository
     */
    private $pick;

    /**
     * @param PickRepository $pick
     */
	public function __construct(PickRepository $pick)
	{
        $this->pick = $pick;
	}

    /**
     * Handles favouriting a pick
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $pick = $this->pick->find($command->pickId);
        if (!$pick) {
            throw new SeException("Pick not found.", 404);
        }

        $pick->favouritedUsers()->detach([\Auth::user()->id]);
        return $pick;
	}
}
