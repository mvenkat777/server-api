<?php
namespace Platform\Collab\Helpers;

use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\CollabRepository;

class DirectMessageHelpers {

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
