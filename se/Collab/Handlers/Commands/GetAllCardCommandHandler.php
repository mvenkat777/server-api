<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\CardRepository;
use Platform\Collab\Repositories\CommentRepository;
use Platform\Collab\Repositories\Repository;
use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\MemberRepository;
use Platform\Collab\Commands\GetAllCollabMembersCommand;
use Platform\App\Commanding\DefaultCommandBus;

/**
* GetAllCardCommand $command
*/
class GetAllCardCommandHandler extends Repository implements CommandHandler
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
	
    function __construct(CommentRepository $commentRepository,DefaultCommandBus $commandBus,CardRepository $cardRepository,
                         MemberRepository $memberRepository)
	{
        $this->commandBus = $commandBus;
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
        $data = [];
		$cards = $this->cardRepository->getAllCards($command->collabId);
        $favouriteCards = $this->memberRepository->getAllByUser(\Auth::user()->id);
        $data['isAuthorised'] = false;
        if($favouriteCards){
            $foundKey = array_search($command->collabId, array_column($favouriteCards->collab, 'collabId'));
            if($foundKey !== false) {
                $this->getCommentCount($cards->message['data']);
                $data['data'] = $this->calculateFavourites(
                    $cards->message['data'], 
                    $favouriteCards->collab[$foundKey]['favourites'],
                    $command->collabId
                );
                $data['isAuthorised'] = true;
                $members = $this->commandBus->execute(new GetAllCollabMembersCommand($command->collabId));
                $data['isAuthorised'] = in_array(\Auth::user()->id, array_column($members, 'id'));
                return $data;
            }
        }

        $members = $this->commandBus->execute(new GetAllCollabMembersCommand($command->collabId));
        $data['isAuthorised'] = in_array(\Auth::user()->id, array_column($members, 'id'));

        $data['data'] = $this->attachCollabIds($cards->message['data'],$command->collabId);
        return $data;
	}

    private function calculateFavourites($cards, $favouriteCards, $collabId)
    {
        foreach($cards as $key => $card) {
            $cards[$key]['isFavourite'] = in_array($card['id'], $favouriteCards);
            $cards[$key]['collabId'] = $collabId;
            $cards[$key]['totalComments'] = $this->getCommentCount($card['id']);
            $cards[$key]['isAuthorised'] = true;
        }
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
    private function attachCollabIds($data, $collabId)
    {
        $collection = [];
        foreach ($data as $key => $value) {
            $collection[$key] = $value;
            $collection[$key]['collabId'] = $collabId;
        }
        return $collection;
    }
}
