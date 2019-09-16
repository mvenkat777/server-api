<?php

namespace Platform\App\Activity\Recorders;

use Carbon\Carbon;
use Platform\App\Activity\Models\GlobalActivity;
use Platform\App\Activity\Models\LineActivity;

class LineActivityRecorder
{
    public function record($changeLogs)
    {
        if (empty($changeLogs['data'])) {
            return;
        }

        $lineId = $changeLogs['lineId'];
        $styleId = $changeLogs['styleId'];

        $links = [];
        foreach ($changeLogs['data'] as $changeLog) {
            array_push($links, [
                'fieldName' => $changeLog['fieldName'],
                'type' => $changeLog['type'],
                'originalValue' => isset($changeLog['originalValue']) ? $changeLog['originalValue'] : null,
                'updatedValue' => $changeLog['updatedValue'],
            ]);
        }

        $line = \App\Line::select('id', 'name')
                           ->where('id', $lineId)
                           ->first()
                           ->toArray();

        $style = \App\Style::select('id', 'name')
                           ->where('id', $styleId)
                           ->first()
                           ->toArray();


        $activity = [
            'version' => 1,
            'objectType' => 'activity',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => [
                'displayName'=> 'Line',
                'systemName'=> 'line',
                'subEntity' => $this->getStyleEntity($style),
                'id' => \App\AppsList::where('app_name', 'line')->first()->id,
                'meta'=> $line,
                'icon'=> 'dashboard',
            ],
            'actor' => [
                "type" => "person",
                "user" => [
                    "id" => \Auth::user()->id,
                    "displayName" => \Auth::user()->display_name,
                    "email" => \Auth::user()->email,
                ]
            ],
            'verb' => 'updated',
            'links' => $links,
            'published'=> Carbon::now()->toDateTimeString()
        ];

        LineActivity::create($activity);
        GlobalActivity::create($activity);
    }

    public function getStyleEntity($style)
    {
        return [
            'displayName'=> 'Style',
            'systemName'=> 'style',
            'meta'=> $style,
        ];
    }
}
