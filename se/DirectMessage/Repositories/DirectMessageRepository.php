<?php
namespace Platform\DirectMessage\Repositories;

use Rhumsaa\Uuid\Uuid;
use App\User;

/**
* Repository class for Direct Message
*/
abstract class DirectMessageRepository
{
	public function getAuth()
	{
		$auth = \Auth::user();
		return [
			'id' => $auth->id,
			'displayName' => $auth->display_name,
			'email' => $auth->email
		];
	}
	public function getUser($attribute, $user)
	{
		$details = User::where($attribute, $user)->first();
		return [
			'id' => $details->id,
			'displayName' => $details->display_name,
			'email' => $details->email
		];
	}

	/**
     * @return string
     */
    public function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }
}