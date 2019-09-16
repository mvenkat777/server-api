<?php

namespace Platform\Tasks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Tasks\Repositories\Contracts\GoogleCalendarRepository;
use App\GoogleCalendar;

class EloquentGoogleCalendarRepository extends Repository implements GoogleCalendarRepository 
{

	public function model(){
		return 'App\GoogleCalendar';
	}
    
    /**
     * Save google calendar details
     *
     * @param array $data
     * @return App\GoogleCalendar
     */
    public function save($data)
    {
        $dbData = [
            'id' => $this->generateUUID(),
            'calendar_id' => $data['calendarId'],
            'event_id' => $data['eventId']
        ];

        return $this->create($dbData);
    }
}
