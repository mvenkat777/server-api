<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\MemberRepository;
use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\Repository;

/**
* To set a card as a favourite card
*/
class SetFavouriteCardCommandHandler extends Repository implements CommandHandler
{
	/**
     * @var Platform\Collab\Repositories\MemberRepository
     */
    private $memberRepository;

    /**
     * @var Platform\Collab\Repositories\CardRepository
     */
    private $cardRepository;
	
	function __construct(MemberRepository $memberRepository, CardRepository $cardRepository)
	{
		$this->memberRepository = $memberRepository;
		$this->cardRepository = $cardRepository;
	}

	/**
     * @param  SetFavouriteCardCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$actorId= \Auth::user()->id;
		
		if($command->flag){
			$status = $this->memberRepository->updateFavourites($actorId, $command->collabId, $command->cardId);
		} else {
			$status = $this->memberRepository->removeFavourites($actorId, $command->collabId, $command->cardId);
		}
		
		if($status){
			return $this->cardRepository->getAllCards($command->collabId);
		} else {
			throw new SeException("Failed to update favourites. Try again with correct input", 422, '9072422');
			
		}
	}
}