<?php

namespace Platform\Authentication\Commands;

use Carbon\Carbon;
use Platform\App\Helpers\Helpers;
use Platform\App\Wrappers\IPLocator;

class UpdateLastLoginLocationCommand
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $location;

    /**
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;

        $ipaddress = Helpers::getIPAddress();
        if ($ipaddress != 'UNKNOWN') {
            try{
                $userLocation = (new IPLocator)->getLocationJson($ipaddress);
            } catch(\Exception $e) {
                $userLocation = new \stdClass;
            }
        }
        $userLocation->browser = Helpers::getBrowser();
        $userLocation->date = Carbon::now()->toDateTimeString();
        $this->location = json_encode($userLocation);
    }
}
