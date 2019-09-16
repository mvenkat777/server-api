<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\Repository;

/* To store the direct conversastion between two user */
class ArchiveCardCommandHandler extends Repository implements CommandHandler{

	/**
     * @var Platform\Collab\Repositories\CardRepository
     */
    private $cardRepository;

    public function __construct(CardRepository $cardRepository)
	{
		$this->cardRepository     = $cardRepository;
	}

	/**
     * @param  ArchiveCardCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		/**
		 * To archive a card of a requsested collab
		 * 
		 */ 
		$status = $this->cardRepository->archive($command->collabId, $command->cardId);
		if($status){
			return $status;
		} else {
			throw new SeException("Something is wrong. Please check your input", 422, '9005500');
		}
	}
}