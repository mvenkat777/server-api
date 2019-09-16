<?php

namespace Platform\Authentication\Transformers;

use App\UserToken;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Platform\Tasks\Transformers\MetaTaskTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Platform\Dashboard\Transformers\ActivityTransformer;

class AuthTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform(UserToken $userToken)
    {
        $user = [
                'id' => $userToken->user->id,
                'displayName' => (string) $userToken->user->display_name,
                'email' => (string) $userToken->user->email,
                'lastLoginLocation' => $userToken->user->last_login_location,
                'isGod' => $userToken->user->is_god,
                'isSe' => $userToken->user->se,
                'isPasswordChangeRequired' => is_null(
                    $userToken->user->is_password_change_required
                ) ? false : $userToken->user->is_password_change_required,
                'createdAt' => $userToken->user
                                         ->created_at
                                         ->toDateTimeString(),
                'updatedAt' => $userToken->user
                                         ->updated_at
                                         ->toDateTimeString(),
                'providers' => $userToken->providers
            ];

        if (isset($userToken->roles)) {
            $user['roles'] = $userToken->roles;
        }

        if (isset($userToken->appsPermissions)) {
            $user['appsPermissions'] = $userToken->appsPermissions;
        }

        if (isset($userToken->activity)) {
            $user['feed']['activity'] = $this->getPaginatedCollection($userToken->activity, new ActivityTransformer);
        } 

        if (isset($userToken->notification)) {
            $user['feed']['notification'] = $userToken->notification;
        } 

        if (isset($userToken->messages)) {
            $user['feed']['messages'] = array_reverse($this->sortByTime($userToken->messages));
        } 

        if (isset($userToken->messagesCount)) {
            $user['feed']['messagesCount'] = $userToken->messagesCount;
        } 

        if (isset($userToken->tasks)) {
            $user['feed']['tasks'] = $this->getPaginatedCollection($userToken->tasks, new MetaTaskTransformer);
        } 

        return [
            'token' => (string) $userToken->token,
            'expiresAt' => $userToken->expires_at,
            'createdAt' => $userToken->created_at->toDateTimeString(),
            'updatedAt' => $userToken->updated_at->toDateTimeString(),
            'user' => $user,
        ];
    }

    /**
     * Get the paginated list of collection
     *
     * @param   Collection  $collection
     * @param   Transformer $callback
     * @param   string      $namespace
     * @return  Array
     */
    private function getPaginatedCollection($collection, $callback, $namespace = '')
    {
        $resource = new Collection($collection, $callback);
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        foreach ($queryParams as $key => $value) {
            $collection->addQuery($key, $value);
        }

        $paginatorAdapter = new IlluminatePaginatorAdapter($collection);
        $resource->setPaginator($paginatorAdapter);

        return $this->fractal->createData($resource)->toArray()['data'];
    }

	/**
	 * To sort an array on the basis of ascending order
	 * @param array collection
	 * @return mixed
	 */
	public function sortByTime($collection)
	{
		usort($collection, function($a, $b) { //Sort the array using a user defined function
            return $a['lastMessage']['createdAt'] > $b['lastMessage']['createdAt'] ? 1 : -1; //Compare the scores
        });                                                                                                                                                                                                        
        return $collection;
	}
}
