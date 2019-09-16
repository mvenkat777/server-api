<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use Platform\App\Activity\Models\LineListProductStream;
use Platform\App\Activity\Models\LineProductStream;
use Platform\App\Activity\Models\ProductStream as ProductStreamModel;
use Platform\App\Activity\Models\SampleProductStream;
use Platform\App\Activity\Models\StyleListProductStream;
use Platform\App\Activity\Models\StyleProductStream;
use Platform\App\Activity\Models\TNAProductStream;
use Platform\App\Activity\Models\TechpackProductStream;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Dashboard\Commands\GetActivityByScopeCommand;
use Platform\Dashboard\Commands\GetAppFeedCommand;
use Platform\Dashboard\Commands\GetNotificationCommand;
use Platform\Dashboard\Commands\UpdateNotificationCommand;
use Platform\Dashboard\ProductStream\ProductStream;
use Platform\Dashboard\Repositories\Contracts\DashboardRepository;
use Platform\Dashboard\Transformers\FeedTaskTransformer;
use Platform\Dashboard\Transformers\ActivityTransformer;
use Platform\Dashboard\Transformers\NotificationTransformer;
use Platform\Tasks\Commands\GetTaskByTypeCommand;
use Platform\Tasks\Transformers\MetaTaskTransformer;
use Platform\Dashboard\Commands\GetNotificationFeedCommand;
use Platform\Dashboard\Transformers\NotificationFeedTransformer;
use Platform\Dashboard\Transformers\NotificationFeedLineTransformer;
use Platform\Dashboard\Commands\GetNotificationFeedByEntityCommand;
use Platform\Dashboard\Transformers\NotificationFeedDetailsTransformer;
use Platform\Dashboard\Transformers\NotificationLineFeedDetailsTransformer;

class DashboardController extends ApiController
{
    private $transformer = [
                'tasks' => 'Platform\Tasks\Transformers\TaskTransformer'
            ];
    /**
     * @param DefalutCommandBus $commandBus
     * @param DashboardRepository $dashboardRepository
     */
    public function __construct(DefaultCommandBus $commandBus,
                                DashboardRepository $dashboardRepository)
    {
        $this->commandBus = $commandBus;
        $this->dashboardRepository = $dashboardRepository;

        parent::__construct(new Manager());
    }

    /**
     * Show the activity according to scope
     *
     * @return \Illuminate\Http\Response
     */
    public function showActivity(Request $request)
    {
        $scope = 'global';
        $items = is_null($request->get('item')) ? 20 : $request->get('item');
        $type = is_null($request->get('type')) ? 20 : $request->get('type');
        $activity = $this->commandBus->execute(new GetActivityByScopeCommand($scope, $items, $type));
        // return $this->respondWithArray([ 'data' => $activity ]);
        return $this->respondWithPaginatedCollection($activity, new ActivityTransformer, 'Activity By Scope');
    }

    /**
     * Show the notification list of an user
     *
     * @return \Illuminate\Http\Response
     */
    public function showNotification(Request $request)
    {
        $items = is_null($request->get('items')) ? 20 : $request->get('items');
        $notification = $this->commandBus->execute(new GetNotificationCommand(\Auth::user(), $items));
        return $this->respondWithArray(['data' => $notification]);
        return $this->respondWithPaginatedCollection($notification, new NotificationTransformer, 'Notification List');
    }

    /**
     * Update the notification list of an user
     *
     * @return \Illuminate\Http\Response
     */
    public function updateNotification(Request $request, $notificationId)
    {
        $items = is_null($request->get('items')) ? 20 : $request->get('items');
        $notification = $this->commandBus->execute(new UpdateNotificationCommand(\Auth::user(), $items, $notificationId));
        return $this->respondWithArray(['data' => $notification]);
        return $this->respondWithPaginatedCollection($notification, new NotificationTransformer, 'Notification List');
    }

    /**
     * Show activity/feed for an app
     *
     * @param \Illuminate\Http\Request  $request
     * @param String                    $appName
     * @return \Illuminate\Http\Response
     */
    public function appFeed(Request $request, $appName)
    {
        $items = is_null($request->get('items')) ? 20 : $request->get('items');
        if ($appName === 'tasks') {
            $tasks = $this->commandBus->execute(
                new GetTaskByTypeCommand($request->all())
            );
            return $this->respondWithPaginatedCollection($tasks,
                new FeedTaskTransformer,
                'task'
            );
        }
        $feedData = $this->commandBus->execute(new GetAppFeedCommand($appName, $items));
        // return $this->respondWithPaginatedCollection($feedData, new $this->transformer[$appName], 'Application News Feed');
    }

    /**
     * Get user notification list
     *
     * @return Mixed
     */
    public function getNotificationFeed(Request $request)
    {
        $fractal = new Manager();
        if($request->all()['entity'] == 'all' && !isset($request->all()['id'])){
          $notification['all'] = $this->commandBus->execute(new GetNotificationFeedCommand(\Auth::user(), 'all'));
          if(!is_null($notification['all'])){
            $allNotification = new Collection($notification['all'], new NotificationFeedTransformer());
            $allNotification = $fractal->createData($allNotification)->toArray();
          }
          $notification['line'] = $this->commandBus->execute(new GetNotificationFeedCommand(\Auth::user(), 'line'));
          if(!is_null($notification['line'])){
            $lineNotification = new Collection($notification['line'], new NotificationFeedLineTransformer());
            $lineNotification = $fractal->createData($lineNotification)->toArray();
          }
          $out = array_merge($allNotification['data'], $lineNotification['data']);
          $sortedEntities = $this->sortByTime($out);
          return $this->respondWithArray(['data' => $sortedEntities]);
        } elseif($request->all()['entity'] == 'all' && isset($request->all()['id'])){
          $notification = $this->commandBus->execute(new GetNotificationFeedByEntityCommand($request->all()['id'], 'all'));
          if(count($notification)){
            return $this->respondWithCollection($notification, new NotificationFeedDetailsTransformer, 'Notif Feed Details');
          }
          $notification = $this->commandBus->execute(new GetNotificationFeedByEntityCommand($request->all()['id'], 'line'));
          if(count($notification))
            return $this->respondWithCollection($notification, new NotificationLineFeedDetailsTransformer, 'Notif Feed Details');
          else
            return [];
        } else{
            return [];
        }
    }

    public function sortByTime($collection)
    {
      usort($collection, function($a, $b) { //Sort the array using a user defined function
              return $a['updatedAt'] > $b['updatedAt'] ? -1 : 1; //Compare the scores
          });
          return $collection;
    }

    /**
     * Get sub entity list of an entity
     *
     * @param   String  $entityId
     * @return  Mixed
     */
    public function getEntityNotificationFeed($entityId)
    {
        $notification = $this->commandBus->execute(new GetNotificationFeedByEntityCommand($entityId));
        return $this->respondWithCollection($notification, new NotificationFeedDetailsTransformer, 'Notif Feed Details');
    }

    public function productStream(Request $request)
    {
        $request = $request->all();

        if (isset($request['lineId']) && isset($request['styleId'])) {
            $stream = $this->getStyleStream($request['lineId'], $request['styleId']);
            return $this->respondWithArray(['data' => $stream]);
        } else if (isset($request['lineId'])) {
            $unArchiveStyleIds = \App\Style::where('line_id', $request['lineId'])
                ->lists('id');
            $styles = StyleListProductStream::where('line_id', $request['lineId'])
                                            ->whereIn('style_id', $unArchiveStyleIds)
                                            ->get();
            return $this->respondWithArray(['data' => $styles]);

        }

        $userId = \Auth::user()->id;
        if (isset($request['type']) && $request['type'] == 'me') {
            $lineIds = \App\Line::where('sales_representative_id', $userId)
                ->orWhere(function($query) use($userId) {
                    $query->orWhere('production_lead_id', $userId)
                        ->orWhere('product_development_lead_id', $userId)
                        ->orWhere('merchandiser_id', $userId);
                })
                ->whereNull('archived_at')
                ->lists('id');
            $stream = LineListProductStream::orderBy('last_updated', 'desc')
                        ->whereIn('line_id', $lineIds)
                        ->get();
        } else {
            $lineIds = \App\Line::whereNull('archived_at')
                ->lists('id');
            $stream = LineListProductStream::orderBy('last_updated', 'desc')
                ->whereIn('line_id', $lineIds)
                ->get();
        }
        return $this->respondWithArray(['data' => $stream]);
        // return $this->respondWithArray(['data' => $stream]);
    }

    public function getStyleStream($lineId, $styleId)
    {
        $stream = [];
        $lineStreams = LineProductStream::where('meta.id', $lineId)
                                          ->orderBy('stream.published', 'desc')
                                          ->get();
        foreach ($lineStreams as $lineStream) {
            array_push($stream, $lineStream->stream);
        }
        $styleStreams = StyleProductStream::where('meta.lineId', $lineId)
                                            ->where('meta.id', $styleId)
                                            ->orderBy('stream.published', 'desc')
                                            ->get();
        foreach ($styleStreams as $styleStream) {
            array_push($stream, $styleStream->stream);
        }

        $techpackStreams = TechpackProductStream::where('meta.lineId', $lineId)
                                                  ->where('meta.styleId', $styleId)
                                                  ->orderBy('stream.published', 'desc')
                                                  ->get();
        foreach ($techpackStreams as $techpackStream) {
            array_push($stream, $techpackStream->stream);
        }

        $sampleStreams = SampleProductStream::where('meta.lineId', $lineId)
                                                  ->where('meta.styleId', $styleId)
                                                  ->orderBy('stream.published', 'desc')
                                                  ->get();
        foreach ($sampleStreams as $sampleStream) {
            array_push($stream, $sampleStream->stream);
        }

        $tnaStreams = TNAProductStream::where('meta.lineId', $lineId)
                                                  ->where('meta.styleId', $styleId)
                                                  ->orderBy('stream.published', 'desc')
                                                  ->get();
        foreach ($tnaStreams as $tnaStream) {
            array_push($stream, $tnaStream->stream);
        }
        return $stream;
    }

}
