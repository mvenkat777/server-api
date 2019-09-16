<?php

namespace Platform\Collab\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;
use Rhumsaa\Uuid\Uuid;

/**
 * Class Repository
 */
abstract class Repository 
{
	/**
     * @return string
     */
    public function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return mixed
     */ 
    public function user($attribute, $key){
    	return \App\User::where($attribute, $key)->first();
    }

    /**
     * @return mixed
     */ 
    public function userFramedData($attribute, $key){
        $user = \App\User::where($attribute, $key)->first();
        return [
            'id' => $user->id,
            'displayName' => $user->display_name,
            'email' => $user->email
        ];
    }

    /**
     * @return mixed or 1
     */ 
    public function queue($value, $model){
        return Queue::push($model->manipulate($value));
    }

    public function getCurrentDateTime()
    {
        return Carbon::parse(Carbon::now())->toDateTimeString();
    }

    /**
     * To generate SE-BOT Message
     */
    public function generateBotMessgae($value)
    {
        /**
         * Here, the participant and initiator actual Id's is comming after getting swapped with each other
         * As because, it is getting overwritten in the overwriteCommand() function.
         *
         */
        $value->participantDisplayName = \App\User::where('id', $value->participant)->first()->display_name;
        $value->initiatorDisplayName = \App\User::where('id', $value->initiator)->first()->display_name;
        return [
            'chatId' => $value->convId,
            'messages' => [
                [
                    'id' => $this->generateUUID(),
                    'message' => 'This is a very begining of your direct message between with "'.$value->participantDisplayName.' and '.$value->initiatorDisplayName.'". Direct messages are private between two of you.',
                    'type' => 'SE-BOT',
                    'isFavourite' => false
                ],
            ],
            'archived' => [
            ]
        ];
    }

    /**
     * To return paginated data
     * @return mixed
     */
    public function paginate($data, $page = 0, $show = 10)
    {
        // $totalPage = intval(ceil((count($data) / $show)));
        // $page = ($page <= $totalPage) ? $page : $totalPage;
        // $currentPage = count($data);
        
        // $paginated['data'] = array_splice($data, ($page * $show), $show);
        $paginated['data'] = $data;
        // $paginated['paginate'] = [
        //     'totalPage' => $totalPage,
        //     'perPage' => $show,
        //     'currentPage' => $page,
        //     'nextPage' => ($page < $currentPage) ? $page+1 : $totalPage
        // ];
        return $paginated;
    }

    /**
     * To create username using email
     * @return string
     */
     public function createUserName($value)
     {
        $userPart = explode("@", $value);
        $username = $userPart[0];
        
        return ucfirst($username);
     } 

    public function convertArrayToString($array)
    {
        return implode(", ",$array);        
    } 

    /**
     * To return paginated data
     * @return mixed
     */
    public function regexParticipant($actualChatId, $matchId)
    {
        $participantId = '';
        if(preg_match("/^(.*)$matchId(.*)$/", $actualChatId, $m)) {
                $participantId= $m[1].$m[2];
        }

        return trim($participantId, '-');
    }  
}