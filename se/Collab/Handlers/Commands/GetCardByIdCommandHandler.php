<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\CommentRepository;
use Platform\Collab\Repositories\Repository;
use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\MemberRepository;

/**
* GetCardByIdCommandHandler $command
* @return mixed
*/
class GetCardByIdCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\CardRepository
     */
    private $cardRepository;

    /**
     * @var Platform\Collab\Repositories\CommentRepository
     */
    private $commentRepository;

    /**
     * @var Platform\Collab\Repositories\MemberRepository
     */
    private $memberRepository;
	
    function __construct(CommentRepository $commentRepository,CardRepository $cardRepository,
                         MemberRepository $memberRepository)
	{
		$this->cardRepository = $cardRepository;
        $this->memberRepository = $memberRepository;
        $this->commentRepository = $commentRepository;
	}

	/**
     * @param  StoreNewCollabCommand $command 
     * @return mixed       
     */
	public function handle($command)
	{
		$cards = $this->cardRepository->getCardByCardId($command->collabId, $command->cardId);
        $favouriteCards = $this->memberRepository->getAllByUser(\Auth::user()->id);
        $foundKey = array_search($command->collabId, array_column($favouriteCards->collab, 'collabId'));
        if($foundKey !== false) {
            $this->getCommentCount($cards->message[0]);
            return $this->calculateFavourites(
                $cards->message[0], 
                $favouriteCards->collab[$foundKey]['favourites'],
                $command->collabId
            );
        }
        return $cards->message[0];
	}

    private function calculateFavourites($cards, $favouriteCards, $collabId)
    {
        $cards['isFavourite'] = in_array($cards['id'], $favouriteCards);
        $cards['collabId'] = $collabId;
        $cards['totalComments'] = $this->getCommentCount($cards['id']);
        return $cards;
    }

    private function getCommentCount($card)
    {
            $count = $this->commentRepository->getAllComments($card);
            if($count)
                return count($count->comment);
            else
                return 0;
           
    }
}