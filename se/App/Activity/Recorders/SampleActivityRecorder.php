<?php

namespace Platform\App\Activity\Recorders;

use Carbon\Carbon;
use Platform\App\Activity\Models\GlobalActivity;
use Platform\App\Activity\Models\SampleActivity;

class SampleActivityRecorder
{
    public function record($changeLogs)
    {
        if (empty($changeLogs['data'])) {
            return;
        }

        $sampleId = $changeLogs['sampleId'];
        $containerId = $changeLogs['containerId'];

        $links = [];
        foreach ($changeLogs['data'] as $changeLog) {
            array_push($links, [
                'fieldName' => $changeLog['fieldName'],
                'type' => $changeLog['type'],
                'originalValue' => isset($changeLog['originalValue']) ? $changeLog['originalValue'] : null,
                'updatedValue' => $changeLog['updatedValue'],
            ]);
        }

        $sampleContainer = \App\SampleContainer::find($containerId);
        $techpack = $sampleContainer->techpack()->first();

        $sampleContainer = [
            'id' => $sampleContainer->id,
            'name' => $techpack->name,
        ];

        $sample = \App\Sample::select('id', 'title as name')
                           ->where('id', $sampleId)
                           ->first()
                           ->toArray();

        $activity = [
            'version' => 1,
            'objectType' => 'activity',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => [
                'displayName'=> 'Sample',
                'systemName'=> 'sample',
                'subEntity' => $this->getSampleEntity($sample),
                'id' => \App\AppsList::where('app_name', 'sample')->first()->id,
                'meta'=> $sampleContainer,
                'icon'=> 'check_box',
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

        SampleActivity::create($activity);
        GlobalActivity::create($activity);
    }

    public function getSampleEntity($sample)
    {
        return [
            'displayName'=> 'Sample',
            'systemName'=> 'sample',
            'meta'=> $sample,
        ];
    }
}
