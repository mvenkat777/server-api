<?php

namespace Platform\App\Activity\Recorders;

use Carbon\Carbon;
use Platform\App\Activity\Models\GlobalActivity;
use Platform\App\Activity\Models\TechpackActivity;

class TechpackActivityRecorder
{
    public function record($changeLogs)
    {
        if (empty($changeLogs['data'])) {
            return;
        }

        $techpackId = $changeLogs['id'];

        $links = [];

        foreach ($changeLogs['data'] as $changeLog) {
            array_push($links, $this->makeLink($changeLog));
        }

        if ((count($links) == 1) && empty(array_values($links)[0])) {
            return;
        }

        $techpack = \App\Techpack::select('id', 'name')
                           ->where('id', $techpackId)
                           ->first()
                           ->toArray();

        $activity = [
            'version' => 1,
            'objectType' => 'activity',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => [
                'displayName'=> 'Techpack',
                'systemName'=> 'techpack',
                'subEntity' => NULL,
                'id' => \App\AppsList::where('app_name', 'techpack')->first()->id,
                'meta'=> $techpack,
                'icon'=> 'layers',
            ],
            'actor' => $this->getActor(),
            'verb' => 'update',
            'links' => $links,
            'published'=> Carbon::now()->toDateTimeString()
        ];

        TechpackActivity::create($activity);
        GlobalActivity::create($activity);
    }

    public function getActor()
    {
        return [
            "type" => "person",
            "user" => [
                "id" => \Auth::user()->id,
                "displayName" => \Auth::user()->display_name,
                "email" => \Auth::user()->email,
            ]
        ];
    }

    public function makeLink($changeLog)
    {
        $pathway = explode('/', $changeLog['pathway']);
        switch ($pathway[0]) {
            case 'billOfMaterials':
                return $this->makeBOMLink($pathway, $changeLog);

            case 'poms':
                return $this->makePOMLink($pathway, $changeLog);

            case 'specSheets':
                return $this->makeGradedSpecsLink($pathway, $changeLog);
        }
    }

    public function makeBOMLink($pathway, $changeLog)
    {
        return [
            'fieldName' => $pathway[0] . '/' . $pathway[1] . '/' . $changeLog['fieldName'] ,
            'type' => 'string',
            'originalValue' => isset($changeLog['originalValue']) ? $changeLog['originalValue'] : null,
            'updatedValue' => $changeLog['updatedValue'],
        ];
    }

    public function makePOMLink($pathway, $changeLog)
    {
        return [
            'fieldName' => $pathway[0] . '/' . $changeLog['fieldName'] ,
            'type' => 'string',
            'originalValue' => isset($changeLog['originalValue']) ? $changeLog['originalValue'] : null,
            'updatedValue' => $changeLog['updatedValue'],
        ];
    }

    public function makeGradedSpecsLink($pathway, $changeLog)
    {
        return [
            'fieldName' => $pathway[0] . '/' . $pathway[count($pathway) - 1] ,
            'type' => 'string',
            'originalValue' => isset($changeLog['originalValue']) ? $changeLog['originalValue'] : null,
            'updatedValue' => $changeLog['updatedValue'],
        ];
    }
}
