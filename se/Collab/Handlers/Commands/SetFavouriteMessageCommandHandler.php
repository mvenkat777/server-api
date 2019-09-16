<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Collab\Commands\GetMessageByIdCommand;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Helpers\DirectMessageHelpers;

/* To store the direct conversastion between two user */
class SetFavouriteMessageCommandHandler extends Repository implements CommandHandler{

	/**
     * @var Platform\Collab\Repositories\DirectMessagePermissionRepository
     */
    private $messagePermissionRepository;

    /**
     * @var Platform\Collab\Repositories\DirectMessageHelpers
     */
    private $helpers;

    public function __construct(DirectMessagePermissionRepository $messagePermissionRepository,
    							DirectMessageHelpers $helpers, DefaultCommandBus $commandBus)
	{
		$this->messagePermissionRepository = $messagePermissionRepository;
		$this->helpers = $helpers;
		$this->commandBus = $commandBus;
	}

	/**
     * @param  ArchiveCardCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		/**
		 * To set or remove a direct message from being favourite
		 * 
		 */ 
		$actorId= \Auth::user()->id;
		$participant = $this->helpers->regexParticipant($command->chatId, $actorId.'-');

		if(!empty($participant)) {
			if($command->flag){
				$status = $this->messagePermissionRepository->updateFavourites($actorId, $command->chatId, $command->messageId, $participant);
			} else {
				$status = $this->messagePermissionRepository->removeFavourites($actorId, $command->chatId, $command->messageId, $participant);
			}
		} else {
			if($command->flag){
				$status = $this->messagePermissionRepository->updateFavouritesForGroup($actorId, $command->chatId, $command->messageId, $command->chatId);
			} else {
				$status = $this->messagePermissionRepository->removeFavouritesForGroup($actorId, $command->chatId, $command->messageId, $command->chatId);
			}
		}
		
		if($status){
			$param = [
				'chatId' => $command->chatId,
				'messageId' => $command->messageId
			];

			return $this->commandBus->execute(new GetMessageByIdCommand($param));
		} else {
			throw new SeException("Failed to set as favourite. Try again", 422, '9006422');
		}
		
	}
}