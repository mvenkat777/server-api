<?php
namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\Card;

/**
 * To perform all the CRUD request related to card
 *
 */
class CardRepository extends Repository {

	/**
	 * @var card
	 */
	protected $card;

	public function __construct(Card $card)
	{
		$this->card = $card;
	}

	/**
	 * To store new card
	 * @return mixed
	 */
	public function store($data)
	{
		$data = [
		'collabId' => $data,
		'message' => [],
		'total' => 0,
		'archive' => []
		];	
		return $this->card->create($data);
	}

	/**
	 * To update existing card
	 * @return mixed
	 */
	public function update($attribute, $collabId, $details){
		$isUpdated = $this->card->where('collabId', $collabId)->push($attribute, [$details]);
		if($isUpdated){
			$totalPresentCards = count($this->card->where('collabId', $collabId)->first()->message);
			return $this->card->where('collabId', $collabId)->update(['total' => $totalPresentCards]);
		}
		else {
			return false;
		}
	}

	public function manipulate($collabId, $data)
	{	
		$isExists = $this->card->where('collabId', $collabId)->first();
		if($isExists){
			return $this->update('message', $collabId, $data);
		} else {
			$this->store($collabId);
			return $this->update('message', $collabId, $data);
		}
	}

	public function getCardByCardId($collabId, $cardId){
		return $this->card->where('collabId', $collabId)->
			project([
				'message' => [
					'$elemMatch' => [
						"id" => $cardId
					]
				]
		])->first();
	}

	public function archive($collabId, $cardId)
	{
		$toArchive = $this->getCardByCardId($collabId, $cardId);
		if(isset($toArchive->message) && $toArchive->message){
			$isArchived = $this->card->where('collabId', $collabId)->pull(['message' =>['id' => $cardId]]);
			
			if($isArchived){
				$data = $toArchive['message'][0];
				$data['lastArchivedOn'] = $this->getCurrentDateTime();
				$data['ArchivedBy'] = $this->userFramedData('id',\Auth::user()->id);

				$this->update('archive', $collabId, $data);
				return $this->getAllCards($collabId);
			}
		} else {
			return false;
		}
	}

	public function getAllCards($collabId, $page = 0, $show = 10){

		$items = $this->card->where('collabId', $collabId)->select('message')->first();
		if($items){
			$items->message = $this->paginate($items->message, 0, 10);
		} else {
			$items = json_decode('{}');
			$items->message = ['data' => []];
		}
		return $items;
	}

	public function updateMessage($collabId, $cardId, $message)
	{
		return $this->card->where('collabId', $collabId)
							 ->where('message.id', $cardId)
							 ->update([
							 	'message.$.data' => $message,
							 	'message.$.isEdited' => true
							 ]);
	}
}