<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\GlobalFilter\Commands\ShowAppEntityByAppNameCommand;
use Platform\GlobalFilter\Transformers\GlobalFilterTransformer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class GlobalFilterController extends ApiController
{

    function __construct(DefaultCommandBus $commandBus, Application $app)
    {
        $this->commandBus = $commandBus;
        $this->app = $app;
        parent::__construct(new Manager());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $response = $this->commandBus->execute(new ShowAppEntityByAppNameCommand($data));
        return $this->respondWithPaginatedCollection($response, new GlobalFilterTransformer, 'filter');
    }

    public function getAllApps()
    {
        $authController = $this->app->make(\App\Http\Controllers\AuthController::class);
        $permissions = $authController->fetchUserRolesPermissions(Auth::user()->id);
        $allowedApps = $this->mapApps($permissions);
        $allowedAppsList = \App\AppsList::whereIn('app_name', $allowedApps)->get();
        return $this->respondWithArray(['data' => $this->listAllApps($allowedAppsList)]);
    }

    public function mapApps($permissions) 
    {
        $map = [
            'admin' => 'admin',
            'techpack' => 'techpack',
            'line' => 'line',
            'sample-management' => 'sampleContainer',
            'tasks' => 'task',
            'materials' => 'material',
            'orders' => 'order',
            'vendors' =>  'vendor',
            'users' => 'user',
            'customers' => 'customer',
            'calendar' => 'calendar',
            'rules' => 'rule',
            'messenger' => 'messenger',
            'pom-sheets' => 'pom',
        ];

        $permissions = $permissions['apps_permissions'];
        $mappedApps = [];

        foreach ($permissions as $permission) {
            if (isset($map[$permission->appSlug])) {
                array_push($mappedApps, $map[$permission->appSlug]);
            }
        }
        return $mappedApps;
    }

    public function listAllApps($allowedApps) 
    {
        $appsList = [];

        foreach ($allowedApps as $app) {
            array_push($appsList, [
                'name' => ucfirst($app->app_name),
                'url' => $app->url,
                'img' => $app->icon, 
            ]);
            if ($app->is_searchable == true) {
                array_push($appsList, [
                    'name' => ucfirst($app->app_name) . ' ' . 'Search',
                    'url' => '',
                    'img' => $app->icon, 
                    'metaName' => ucfirst($app->app_name),
                    'apiName' => $app->app_name,
                ]);
            }
        }
        return $appsList;
    }
}
