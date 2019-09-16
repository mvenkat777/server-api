<?php
namespace Platform\Observers;

use Platform\App\Activity\ActivityObserver;

class UserTokenObserver extends ActivityObserver
{
    /**
     * @param array $model
     * @return mixed
     */
    public function created($model)
    {
        $userDetails = $this->getUserDetails($model);
        $verb = 'created';
        $object = [
                'objectType' => 'token created',
                'message' => 'User with email id '.$userDetails->email.' has logged in.'
            ];
        $this->setActivityVerb($verb)->setObject($object)->create($model);
        return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function deleted($model)
    {
        $userDetails = $this->getUserDetails($model);
        $key = 'token';
        $object = [
                    'objectType' => $key.' deleted',
                    'message' => 'User with email id '.$userDetails->email.' has logged out.'
                ];
        $this->setObjectKey($key)->setObject($object)->delete($model);
        return "Successfully Added To Mongo DB.";
    }
}
