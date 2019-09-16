<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\Repository;

/**
* To update a card content
*/
class UpdateCardCommandHandler extends Repository implements CommandHandler
{
	/**
	 * @var card
	 */
	protected $cardRepository;

	public function __construct(CardRepository $cardRepository)
	{
		$this->cardRepository = $cardRepository;
	}

	/**
     * @param  ArchiveCardCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		$chcekPermission = $this->permission($command->collabId, $command->cardId);
		if($chcekPermission){
			$card = $this->cardRepository->updateMessage($command->collabId, $command->cardId, $command->message);
			if($card){
				return $this->cardRepository->getCardByCardId($command->collabId, $command->cardId);
			} else {
				throw new SeException("Failed to update. Try again", 422,'9002422');
				
			}	
		} else {
			throw new SeException("you don't have permission", 401, '9002401');	
		}
	}

	public function permission($collabId, $cardId)
	{
		$data = $this->cardRepository->getCardByCardId($collabId, $cardId)->message[0];
		if($data['owner']['id'] == \Auth::user()->id){
			return true;
		} else {
			return false;
		}
	}
}