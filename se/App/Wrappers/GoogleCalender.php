<?php 

namespace Platform\App\Wrappers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use App\Helpers;
/**
 * Class Google Calender.
 */

class GoogleCalender {

    protected $service;
    protected $summary;
    protected $location; 
    protected $dateStart; 
    protected $dateEnd;
    protected $client;

    protected $description;
    
     /* Getting config */

    function __construct() {
        $client_id = '110556144258422287209';
        $service_account_name = 'se-console@sourceeasy-platform.iam.gserviceaccount.com';
        $key_file_location = base_path() . '/resources/assets/SE_client_secret.json';
         
        $this->client = new \Google_Client();
        $this->client->setApplicationName("SE Platform");

        $this->service = new \Google_Service_Calendar($this->client);
        
        /* If we have access token */

        if (\Cache::has('service_token')) {
          $this->client->setAccessToken(Cache::get('service_token'));
        }
        $key = file_get_contents($key_file_location);

        /* Add the scopes you need */
        $scopes = array('https://www.googleapis.com/auth/calendar');
        $cred = new \Google_Auth_AssertionCredentials(
            $service_account_name,
            $scopes,
            $key
        );

        $cred->privateKey = json_decode($cred->privateKey)->private_key;
        
        $this->client->setAssertionCredentials($cred);
        
        if ($this->client->getAuth()->isAccessTokenExpired()) {
          $this->client->getAuth()->refreshTokenWithAssertion($cred);
        }
        
        Cache::forever('service_token', $this->client->getAccessToken());
    }
             
    public function getSummary()
    {
        return $this->summary;
    }
    /**
     * @param string $summary
     *
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }
    public function getDateStart()
     {
        return $this->dateStart;
     }
     public function setDateStart($dateStart)
     {
        $this->dateStart = $dateStart;
        return $this;
     }
    
     public function getDateEnd()
     {
        return $this->dateEnd;
     }
     public function setDateEnd($dateEnd)
     {
      $this->dateEnd = $dateEnd;
      return $this;
     }

     public function setDescription($description)
     {
         $this->description = $description;
         return $this;
     }

     public function insert($receiverEmail, $summary, $startDate, $endDate=null)
     {
         if(!\Platform\App\Helpers\Helpers::isSeEmail($receiverEmail)) {
             return;
         }
         $event = new \Google_Service_Calendar_Event();
         $event->setSummary($summary);
         if(!is_null($this->description)) {
             $event->setDescription($this->description);
         }
         //$event->setLocation($location);

         $start = new \Google_Service_Calendar_EventDateTime();
         $start->setDate($startDate);
         $event->setStart($start);

         $end = new \Google_Service_Calendar_EventDateTime();
         $endDate = is_null($endDate) ? $startDate : $endDate;
         $end->setDate($endDate);
         $event->setEnd($end);

         $attendee = new \Google_Service_Calendar_EventAttendee();
         $attendee->setEmail($receiverEmail);

         $attendees = array($attendee);
         $event->attendees = $attendees;

         return $this->service->events->insert('sparklefashion.net_gh6ldr1l2me1m0h46s1pjk8go8@group.calendar.google.com', $event, ['sendNotifications' => false]);
    }
}
