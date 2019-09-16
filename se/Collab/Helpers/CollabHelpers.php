<?php
namespace Platform\Collab\Helpers;

use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\CollabRepository;
use Platform\Collab\Commands\GetAllCardCommand;
use Platform\App\Commanding\DefaultCommandBus;

class CollabHelpers extends Repository{

	/**
     * @var data
     */
	public $data = [];

	public $repository;
	
	public $collabRepository;

	/**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

	public function __construct(CollabRepository $collabRepository, DefaultCommandBus $commandBus){
        $this->collabRepository = $collabRepository;
        $this->commandBus = $commandBus;
    }

	/**
	 * To take data and convert it into object
	 * @param  $data 
	 * @return array        
	 */
	// public function structureCollabInput($data){
	// 	foreach ($data->members as $key => $value) {
	// 		$container = $this->repository->getUserById($value);
	// 		$this->data[$key]['id'] = $container->id;
	// 		$this->data[$key]['email'] = $container->email;
	// 		$this->data[$key]['displayName'] = $container->display_name;
	// 	}
	// 	$data->members = $this->data;
	// 	$data->dateTime = $this->repository->getCurrentDateTimeStamp();
	// 	$data->owner = $this->repository->getActorDetails();
	// 	return $data;
	// }

	// public function structurePageInput($data, $isDefault = false) {
	// 	$data->title = $data->title.' : Default Page';
	// 	$data->description = $data->description.' : Default Page';
	// 	$data->collabId = $data->collabId;
	// 	$data->isDefault = $isDefault;
	// 	$data->cardObjectId = [];
	// 	return $data;
	// }

	// public function structureCollabUpdate(array $collabId, $field, $value){
	// 	if(isset($collabId[$field])){
	// 		array_push($collabId[$field], $value);
	// 		$collabId[$field] = array_unique($collabId[$field]);
	// 		return $collabId;
	// 	}
	// 	$collabId[$field] = [];
	// 	array_push($collabId[$field], $value);
	// 	return $collabId;
	// }

	// public function rollbackCollabUpdate(array $collabId, $field, $value){
	// 	if(isset($collabId[$field])){
	// 		if (($key = array_search($value, $collabId[$field])) !== false) {
	// 		    unset($collabId[$field][$key]);
	// 		}
	// 		return $collabId;
	// 	}
	// 	return;
	// }

	// public function structurePageUpdate(array $pageId, $field, $value){
	// 	$pageId[$field] = [];
	// 	array_push($pageId[$field], $value);
	// 	if(isset($pageId[$field])){
	// 		array_push($pageId[$field], $value);
	// 		$pageId[$field] = array_unique($pageId[$field]);
	// 		return $pageId;
	// 	}
	// 	$pageId[$field] = [];
	// 	array_push($pageId[$field], $value);
	// 	return $pageId;
	// }

    public function formatCollab(array $collabs)
    {
        $collabs = $this->sortArray($collabs, 'createdAt', 'DESC');
        if(count($collabs) > 0) {
            $collabs[0]['cards'] = $this->commandBus->execute(new GetAllCardCommand($collabs[0]['collabId']));
        }

        return $collabs;
    }

    public function formatDirectMessage($message)
    {
        $data['chatId'] = $message->chatId;
        $data['isGroup'] = isset($message->isGroup)?$message->isGroup:false;
        if(isset($message->user)) {
            $data['user'] = $message->user;
        }
        $data['data'] = array_map(function($msg) use($message) {
            if(is_object($msg))
                $msg = (array)$msg;
            $requiredData = array(
                'id' => $msg['id'],
                'data' => $msg['message'],
                'urlMeta' => isset($msg['urlMeta'])?$msg['urlMeta']:NULL,
                'type' => $msg['type'],
                'isFavourite' => $msg['isFavourite'],
                'isMedia' => isset($msg['isMedia'])?$msg['isMedia']:false,
                'owner' => isset($msg['owner']) ? $msg['owner'] : null,
                'isEdited' => isset($msg['isEdited']) ? $msg['isEdited'] : null,
                'isCollab' => false,
                'chatId' => $message->chatId,
                'createdAt' => isset($msg['createdAt']) ? $msg['createdAt'] : null
            ); 
            return $requiredData;
        }, $message->messages);

        return $data;
    }

    public function formatSingleDirectMessage($message)
    {
        if(isset($message->user)) {
            $data['user'] = $message->user;
        }
        $data['data'] = array_map(function($msg) use($message) {
            if(is_object($msg))
                $msg = (array)$msg;
            $requiredData = array(
                'id' => $msg['id'],
                'data' => $msg['message'],
                'urlMeta' => isset($msg['urlMeta'])?$msg['urlMeta']:NULL,
                'type' => $msg['type'],
                'isFavourite' => $msg['isFavourite'],
                'isMedia' => isset($msg['isMedia'])?$msg['isMedia']:false,
                'owner' => isset($msg['owner']) ? $msg['owner'] : null,
                'isEdited' => isset($msg['isEdited']) ? $msg['isEdited'] : null,
                'isCollab' => false,
                'isGroup' => isset($message->isGroup)?$message->isGroup:false,
                'chatId' => $message->chatId,
                'createdAt' => isset($msg['createdAt']) ? $msg['createdAt'] : null
            ); 
            return $requiredData;
        }, $message->messages);

        return $data['data'][0];
    }

    public function sortArray(array $array, $column, $by = 'ASC')
    {
        if(strtolower($by) === 'asc') {
            usort($array, function($a, $b) use($column) { 
                return $a[$column] > $b[$column] ? 1 : -1; 
            });
        } else {
            usort($array, function($a, $b) use($column) { 
                return $a[$column] < $b[$column] ? 1 : -1; 
            });
        }

        return $array;
    }

    public function sortJson(array $array, $column, $by = 'ASC')
    {
        if(strtolower($by) === 'asc') {
            usort($array, function($a, $b) use($column) { 
                return $a->$column > $b->$column ? 1 : -1; 
            });
        } else {
            usort($array, function($a, $b) use($column) { 
                return $a->$column < $b->$column ? 1 : -1; 
            });
        }

        return $array;
    }

}
