<?php

namespace Platform\App\Wrappers;

use GeoIp2\Database\Reader;

class IPLocator
{
    protected $reader;

    protected $details;

    public function __construct()
    {
        //dd(base_path());
        //$this->location = \geoip_open(base_path()."/resources/assets/GeoLiteCity.dat", GEOIP_STANDARD); 
        $this->reader = new Reader(base_path()."/resources/assets/GeoLite2-City.mmdb"); 
        $this->makeDefaultDetails();
    }

    private function makeDefaultDetails()
    {
        $this->details = new \stdClass;
        $this->details->ip = null;
        $this->details->city = '';
        $this->details->country = '';
        $this->details->countryCode = '';
        $this->details->location = '';
        $this->details->timeZone = '';
        $this->details->postalCode = '';
    }

    public function getLocationJson($ip)
    {
        try {
            $record = $this->reader->city($ip);
            $this->details->ip = $ip;
            $this->details->city = $record->city->name;
            $this->details->country = $record->country->name;
            $this->details->countryCode = $record->country->isoCode;
            $this->details->location = $record->location->latitude.', '.$record->location->longitude;
            $this->details->timeZone = $record->location->timeZone;
            $this->details->postalCode = $record->postal->code;
        } catch(\Exception $e) {
            $this->details->ip = $ip;
        }
        return $this->details;
    }
}
