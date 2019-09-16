<?php

namespace Platform\Dashboard\ProductStream;

use App\Line;
use Carbon\Carbon;
use Platform\App\Activity\Models\LineActivity;
use Platform\App\Activity\Models\LineListProductStream;
use Platform\App\Activity\Models\LineProductStream;
use Platform\App\Activity\Models\ProductStream as ProductStreamModel;
use Platform\App\Activity\Models\SampleActivity;
use Platform\App\Activity\Models\SampleProductStream;
use Platform\App\Activity\Models\SamplecontainerActivity;
use Platform\App\Activity\Models\StyleListProductStream;
use Platform\App\Activity\Models\StyleProductStream;
use Platform\App\Activity\Models\TNAProductStream;
use Platform\App\Activity\Models\TechpackActivity;
use Platform\App\Activity\Models\TechpackProductStream;
use Platform\App\Activity\Models\CalenderActivity;
use Vinkla\Pusher\Facades\Pusher;

class ProductStream
{
    private $currentData;
    private $lineIds;

    function __construct()
    {
        $this->currentData = LineListProductStream::all();
        //$this->lineIds = Line::lists('id')->toArray();
    }

    /**
     * Make the product stream
     * @return array
     */
    public function make()
    {
        LineListProductStream::truncate();
        StyleListProductStream::truncate();
        LineProductStream::truncate();
        SampleProductStream::truncate();
        StyleProductStream::truncate();
        TNAProductStream::truncate();
        TechpackProductStream::truncate();

        $lineIds = $this->lineIds;
        $productStream = [];

        foreach ($lineIds as $lineId) {
            $this->createLineProductStream($lineId);
        }
        $this->pushTheUpdates();
        return $productStream;
    }

    /**
     * [createLineProductStream description]
     * @param  string $lineId
     * @return mixed
     */
    public function createLineProductStream($lineId)
    {
        $count = 0;
        $lastUpdated = null;

        $lineActivities = $this->getLineActivities($lineId);
        $line = \App\Line::find($lineId);
        $count += count($lineActivities);
        if (count($lineActivities) > 0) {
            foreach ($lineActivities as $lineActivity) {
                $published = Carbon::parse($lineActivity->published);
                $lastUpdated = $this->setPublishedDate($lastUpdated, $published);
                LineProductStream::create([
                    'meta' => [
                        'id' => $line->id,
                        'name' => $line->name,
                    ],
                    'stream' => $this->makeLineActivity($lineActivity)
                ]);
            }
        }

        $styleActivities = $this->getStyleActivities($lineId);
        $count += count($styleActivities);
        if (count($styleActivities) > 0) {
            foreach ($styleActivities as $styleActivity) {
                $published = Carbon::parse($styleActivity->published);
                $lastUpdated = $this->setPublishedDate($lastUpdated, $published);
                $styleId = $styleActivity['entity']['subEntity']['meta']['id'];
                $styleName = $styleActivity['entity']['subEntity']['meta']['name'];
                $styleList = StyleListProductStream::where('line_id', $line->id)
                                                     ->where(
                                                        'style_id',
                                                        $styleId
                                                    )->first();
                if (!$styleList) {
                    StyleListProductStream::create([
                        'line_id' => $line->id,
                        'style_id' => $styleId,
                        'style_name' => $styleName,
                        'last_updated' => $lastUpdated->toDateTimeString(),
                    ]);
                } else {
                    $updated = $styleList->last_updated;
                    if (!empty($updated)) {
                        if ($lastUpdated->gt(Carbon::parse($updated))) {
                            $styleList->last_updated = $lastUpdated->toDateTimeString();
                            $styleList->update();
                        }
                    } else {
                        $styleList->last_updated = $lastUpdated->toDateTimeString();
                        $styleList->update();
                    }
                }


                StyleProductStream::create([
                    'meta' => [
                        'lineId' => $line->id,
                        'id' => $styleId,
                        'name' => $styleName,
                    ],
                    'stream' => $this->makeActivity($styleActivity)
                ]);
            }
        }

        $techpackActivities = $this->getTechpackActivities($lineId);
        $count += $techpackActivities['count'];
        $lastUpdated = $this->setPublishedDate(
            $lastUpdated,
            $techpackActivities['lastUpdated']
        );

        // Make the line list
        if ($count > 0) {
            LineListProductStream::create([
                'line_id' => $lineId,
                'name' => $line->name,
                'last_updated' => $lastUpdated->toDateTimeString(),
                'count' => $count,
            ]);
        }
    }

    /**
     * Push the new update aggregate
     * @return null
     */
    public function pushTheUpdates()
    {
        $updatedStream = LineListProductStream::all();
        $updates = [];
        foreach ($updatedStream as $stream) {
            $exists = $this->currentData->where('line_id', $stream->line_id)
                                        ->first();
            if ($exists) {
                if (($stream->count - $exists->count) > 0) {
                    array_push(
                        $updates,
                        $update = [
                            'line_id' => $exists->line_id,
                            'name' => $exists->name,
                            'last_updated' => $exists->last_updated,
                            'count' => $stream->count - $exists->count,
                            'updated_at' => $exists->updated_at->toDateTimeString(),
                            'created_at' => $exists->created_at->toDateTimeString(),
                        ]
                    );
                }
            } else {
                array_push(
                    $updates,
                    $stream
                );
            }
        }
        if(count($updates) > 0) {
            Pusher::trigger('product-stream', 'product-stream-update', $updates);
        }
    }

    /**
     * Get line activities
     * @param  string $lineId
     * @return mixed
     */
    public function getLineActivities($lineId)
    {
        return LineActivity::where('entity.meta.id', $lineId)
                             ->whereNull('entity.subEntity')
                             ->get();
    }

    /**
     * Get all the style only activities
     * @param  string $lineId
     * @return mixed
     */
    public function getStyleActivities($lineId)
    {
        return LineActivity::where('entity.meta.id', $lineId)
                      ->whereNotNull('entity.subEntity')
                      ->get();
    }

    /**
     * Set the last updated date for a line
     * @param Carbon $lastUpdated
     * @param Carbon $published
     */
    public function setPublishedDate($lastUpdated, $published)
    {
        if (is_null($lastUpdated)) {
            return $published;
        } else if (is_null($published)) {
            return $lastUpdated;
        } else if ($published->gt($lastUpdated)) {
            return $published;
        }
        return $lastUpdated;
    }

    /**
     * Make the line activity stream
     * @param  array $activity
     * @return array
     */
    public function makeLineActivity($activity)
    {
        return [
            'version' => 1,
            'objectType' => 'productStream',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => $activity['entity'],
            'actor' => $this->getActor($activity),
            'verb' => $this->getVerb($activity),
            'links' => $this->getLinks($activity),
            'published'=> $activity['published'],
        ];
    }

    /**
     * Aggregate all the techpack and realted entity activities
     * @param  string $lineId
     * @return mixed
     */
    public function getTechpackActivities($lineId)
    {
        $techpackIds = [];
        $sampleContainers = [];
        $TNAs = [];
        $activities = [];
        $count = 0;
        $lastUpdated = null;

        $styles = Line::find($lineId)->styles()->with('techpack')->get();
        foreach ($styles as $style) {
            $styleCount = 0;

            $styleMeta = [
                'lineId' => $lineId,
                'styleId' => $style->id,
            ];
            if ($style->techpack) {
                $techpackActivities = TechpackActivity::where(
                    'entity.meta.id',
                    $style->techpack->id
                )->get();
                if (count($techpackActivities) > 0) {
                    foreach ($techpackActivities as $techpackActivity) {
                        $published = Carbon::parse($techpackActivity->published);
                        $lastUpdated = $this->setPublishedDate($lastUpdated, $published);
                        TechpackProductStream::create([
                            'meta' => $styleMeta,
                            'stream' => $this->makeActivity($techpackActivity)
                        ]);
                        $count++;
                        $styleCount++;
                    }
                }

                if ($style->techpack->sampleContainer) {
                    foreach ($style->techpack->sampleContainer->samples() as $sample) {
                        $sampleActivities = SampleActivity::where('entity.meta.id', $sample->id)
                                                         ->orWhere('entity.subEntity.meta.id', $sample->id)
                                                         ->get();
                        if (count($sampleActivities) > 0) {
                            foreach ($sampleActivities as $sampleActivity) {
                                $published = Carbon::parse($sampleActivity->published);
                                $lastUpdated = $this->setPublishedDate($lastUpdated, $published);
                                SampleProductStream::create([
                                    'meta' => $styleMeta,
                                    'stream' => $this->makeActivity($sampleActivity)
                                ]);
                                $count++;
                            }
                            $styleCount++;
                        }

                    }
                }

                if (count($style->techpack->TNA) > 0) {
                    foreach ($style->techpack->TNA() as $tna) {
                        $tnaActivities = CalenderActivity::whereIn('entity.meta.id', $tna->id)
                                                      ->get();
                        if (count($tnaActivities) > 0) {
                            foreach ($tnaActivities as $tnaActivity) {
                                $published = Carbon::parse($tnaActivity->published);
                                $lastUpdated = $this->setPublishedDate($lastUpdated, $published);
                                TNAProductStream::create([
                                    'meta' => $styleMeta,
                                    'stream' => $this->makeActivity($tnaActivity)
                                ]);
                            }
                            $styleCount++;
                        }
                        $count++;
                    }
                }
            }
            if ($styleCount > 0) {
                $styleList = StyleListProductStream::where('line_id', $lineId)
                                                     ->where(
                                                            'style_id',
                                                            $style->id
                                                     )->first();
                if (!$styleList) {
                    StyleListProductStream::create([
                        'line_id' => $lineId,
                        'style_id' => $style->id,
                        'style_name' => $style->name,
                        'last_updated' => $lastUpdated->toDateTimeString(),
                    ]);
                } else {
                    $updated = $styleList->updated_at;
                    if (!empty($updated)) {
                        if ($lastUpdated->gt($updated)) {
                            $styleList->last_updated = $lastUpdated->toDateTimeString();
                            $styleList->update();
                        }
                    } else {
                        $styleList->last_updated = $lastUpdated->toDateTimeString();
                        $styleList->update();
                    }
                }
            }
        }

        return [
            'count' => $count,
            'lastUpdated' => $lastUpdated
        ];
    }

    /**
     * Make a single activity object
     * @param  array $activity
     * @return array
     */
    public function makeActivity($activity)
    {
        return [
            'version' => 1,
            'objectType' => 'productStream',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => $activity['entity'],
            'actor' => $this->getActor($activity),
            'verb' => $this->getVerb($activity),
            'links' => $this->getLinks($activity),
            'published'=> $activity['published'],
        ];
    }

    /**
     * Get the sub enttiyr from the item
     * @param  array $activity
     * @return array
     */
    public function getSubEntity($activity)
    {
        return $activity['entity']['subEntity'];
    }

    /**
     * Get the actor from activity
     * @param  array $activity `
     * @return array           `
     */
    public function getActor($activity)
    {
        return $activity['actor'];
    }

    /**
     * Get the verb from the activity
     * @param  array $activity
     * @return string
     */
    public function getVerb($activity)
    {
        return $activity['verb'];
    }

    /**
     * Get the links from the activity
     * @param  array $activity
     * @return $arrayName = array('' => , );
     */
    public function getLinks($activity)
    {
        $links = $activity['links'];
        $objectFields = ['vlpAttachments', 'flat'];
        $transformedLinks = [];
        foreach ($links as $link) {
            $tempLink = [];
            $tempLink['type'] = $link['type'];
            if (isset($link['data'])) {
                $tempLink['data'] = $link['data'];
            } else {
                $tempLink['fieldName'] = $link['fieldName'];
                if (in_array($link['fieldName'], $objectFields)) {
                    $tempLink['originalValue'] = empty($link['originalValue']) ?
                                                    $link['originalValue'] :
                                                    json_decode($link['originalValue']);
                    $tempLink['updatedValue'] = empty($link['updatedValue']) ?
                                                    $link['updatedValue'] :
                                                    json_decode($link['updatedValue']);

                } else {
                    $tempLink['originalValue'] = $link['originalValue'];
                    $tempLink['updatedValue'] = $link['updatedValue'];
                }
            }

            array_push($transformedLinks, $tempLink);
        }
        return $transformedLinks;
    }
}