<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;
use Carbon\Carbon;
use League\Fractal\Manager;
use Platform\Line\Transformers\SalesStreamTransformer;
use League\Fractal\Resource\Collection;

class ReportLog extends SeConsole
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->receivers = ['kishan@sourceeasy.com', 'chirag@sourceeasy.com', 'pranay@sourceeasy.com'];
        //$this->receivers = ['kishan@sourceeasy.com'];
        $this->subject = 'Reports on Platform';
        $this->signature = 'report:log {--type=}';
        $this->description = 'Report the user and app log to gods';
        parent::__construct();

        $this->api_key = 'a0b4744909aafd1453435bc370e3b3c4';
        $this->api_secret = '1c9f0fdc127f1fee383bdad211120923';
        $this->version = '2.0';
        $this->api_url = 'http://data.mixpanel.com/api';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         
        $type = $this->option('type');
        
        $logs = \Platform\App\Activity\Models\LogUser::where('created_at', '>', new \DateTime('-7 day'))->get()->toArray();
        //$logs = \Platform\App\Activity\Models\LogUser::all()->toArray();
        $accessedUsers = array_values(array_diff(array_unique(array_column($logs, 'email')), ['anonymous']));

        $userData = $this->getUserSpecificLog($accessedUsers);
        $appsData = $this->getAppSpecificLog($accessedUsers);

        $wipCsvPath = $this->generateWipCsv();

        \Mail::send('emails.PlatformReport', ['userLog' => $userData, 'appsData' => $appsData, 'users' => $accessedUsers], function ($message) use($wipCsvPath) {
            $message->to($this->receivers)->subject($this->subject);
            if($wipCsvPath) {
                $message->attach($wipCsvPath);
            }
        });

        if($wipCsvPath) {
            \File::delete($wipCsvPath);
        }
    }

    // write a command for this function
    private function generateWipCsv()
    {
        $styles = \App\Style::all();
        $resource = new Collection($styles, new SalesStreamTransformer, 'WIP tracker');
        $rootScope = (new Manager())->createData($resource);
        $data = $rootScope->toArray()['data'];
        $csv = \Excel::create('wiptracker', function($excel) use($data) {
            $excel->sheet('WIP', function($sheet) use($data) {
                $sheet->loadView('exports.reports.WIPTracker')->with(['data' => $data]);
            });
        })->store('csv', false, true);
        return $csv['full'];
    }

    /**
     * Get log of users interaction
     *
     * @param array $accessedUsers
     * @return array
     */
    private function getUserSpecificLog($accessedUsers)
    {
        $yesterDay = new \DateTime(Carbon::yesterday()->toDateTimeString());
        $today = new \DateTime(Carbon::today()->toDateTimeString());
        $logData = [];
        foreach($accessedUsers as $user) {
            $userLog = \Platform\App\Activity\Models\LogUser::where('created_at', '>', new \DateTime('-7 day'))
                                                            ->where('created_at', '<', $today)
                                                            ->where('email', $user)->get()->toArray();
            $data['requestCount'] = count($userLog);
            $data['apps'] = [];


            $userApps = array_values(array_unique(array_column($userLog, 'app')));
            $statusCodes = array_column($userLog, 'statusCode');
            foreach($userApps as $app) {
                list($createdCount, $updatedCount, $deletedCount) = $this->getUserAppData($userLog, $app);

                if($createdCount !== 0 || $updatedCount !== 0 || $deletedCount !== 0) {
                    $data['apps'][$app]['createdCount'] = $createdCount;
                    $data['apps'][$app]['updatedCount'] = $updatedCount;
                    $data['apps'][$app]['deletedCount'] = $deletedCount;
                }
            }

            $logData[$user] = $data;
        }
        
        return $logData;
    }

    /**
     * Get the app data of user
     *
     * @param array $userLog
     * @param string $app
     * @return array
     */
    private function getUserAppData($userLog, $app, $t = false)
    {
        $appUserData = $this->extractRequiredValues($userLog, 'app', $app);

        $postData = $this->extractRequiredValues($appUserData, 'requestType', 'post');
        $putData = $this->extractRequiredValues($appUserData, 'requestType', 'put');
        $deleteData = $this->extractRequiredValues($appUserData, 'requestType', 'delete');

        //$createdCount = count(array_keys(array_column($postData, 'statusCode'), 201));
        $createdCount = count($this->extractRequiredKeys($postData, 'statusCode', 201));
        //$updatedCount = count(array_keys(array_column($postData, 'statusCode'), 200));
        $updatedCount = count($this->extractRequiredKeys($postData, 'statusCode', 200));
        //$putCount = count(array_keys(array_column($putData, 'statusCode'), 200));
        $putCount = count($this->extractRequiredKeys($putData, 'statusCode', 200));
        $updatedCount = $updatedCount + $putCount;
        //$deletedCount = count(array_keys(array_column($deleteData, 'statusCode'), 200));
        $deletedCount = count($this->extractRequiredKeys($deleteData, 'statusCode', 200));

        return [$createdCount, $updatedCount, $deletedCount];
    }

    /**
     * Extract onlky required values from array
     *
     * @param array $bigData
     * @param string $column
     * @param string $key
     * @return array
     */
    private function extractRequiredValues($bigData, $column, $key)
    {
        $keys = $this->extractRequiredKeys($bigData, $column, $key);
        return array_values(array_intersect_key($bigData, array_flip($keys)));
    }

    /**
     * Extract the keys required for values
     *
     * @param array $bigData
     * @param string $column
     * @param string $key
     * @return array
     */
    private function extractRequiredKeys($data, $column, $key)
    {
        return array_keys(array_column($data, $column), $key);
    }

    /**
     * Get log data according to apps
     *
     * @param array $accessedUsers
     * @return array
     */
    private function getAppSpecificLog($accessedUsers)
    {
        //$fromDate = Carbon::parse(Carbon::yesterday());
        $fromDate = Carbon::now()->subWeek();
        $toDate = Carbon::parse(Carbon::today());

        $yesterdayCreatedTechpack = \App\Techpack::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayCreatedLine = \App\Line::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayCreatedCalendar = \Platform\TNA\Models\TNA::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayCreatedTasks = \App\Task::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayCreatedCustomers = \App\Customer::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayCreatedVendors = \App\Vendor::whereBetween('created_at', [$fromDate, $toDate])->get()->toArray();

        $yesterdayUpdatedTechpack = \App\Techpack::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayUpdatedLine = \App\Line::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayUpdatedCalendar = \Platform\TNA\Models\TNA::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayUpdatedTasks = \App\Task::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayUpdatedCustomers = \App\Customer::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayUpdatedVendors = \App\Vendor::whereBetween('updated_at', [$fromDate, $toDate])->get()->toArray();

        $yesterdayUpdatedTechpack = array_intersect_key($yesterdayUpdatedTechpack, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedTechpack, 'id'), array_column($yesterdayCreatedTechpack, 'id')))));
        $yesterdayUpdatedLine = array_intersect_key($yesterdayUpdatedLine, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedLine, 'id'), array_column($yesterdayCreatedLine, 'id')))));
        $yesterdayUpdatedCalendar = array_intersect_key($yesterdayUpdatedCalendar, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedCalendar, 'id'), array_column($yesterdayCreatedCalendar, 'id')))));
        $yesterdayUpdatedTasks = array_intersect_key($yesterdayUpdatedTasks, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedTasks, 'id'), array_column($yesterdayCreatedTasks, 'id')))));
        $yesterdayUpdatedCustomers = array_intersect_key($yesterdayUpdatedCustomers, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedCustomers, 'id'), array_column($yesterdayCreatedCustomers, 'id')))));
        $yesterdayUpdatedVendors = array_intersect_key($yesterdayUpdatedVendors, array_flip(array_keys(array_diff(array_column($yesterdayUpdatedVendors, 'id'), array_column($yesterdayCreatedVendors, 'id')))));

        $yesterdayDeletedTechpack = \App\Techpack::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayDeletedLine = \App\Line::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayDeletedCalendar = \Platform\TNA\Models\TNA::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayDeletedTasks = \App\Task::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayDeletedCustomers = \App\Customer::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();
        $yesterdayDeletedVendors = \App\Vendor::withTrashed()->whereBetween('deleted_at', [$fromDate, $toDate])->get()->toArray();

        $data['line']['created'] = count($yesterdayCreatedLine);
        $data['line']['updated'] = count($yesterdayUpdatedLine);
        $data['line']['total'] = \App\Line::count();
        $data['techpack']['created'] = count($yesterdayCreatedTechpack);
        $data['techpack']['updated'] = count($yesterdayUpdatedTechpack);
        $data['techpack']['total'] = \App\Techpack::count();
        $data['calendar']['created'] = count($yesterdayCreatedCalendar);
        $data['calendar']['updated'] = count($yesterdayUpdatedCalendar);
        $data['calendar']['total'] = \Platform\TNA\Models\TNA::count();
        $data['task']['created'] = count($yesterdayCreatedTasks);
        $data['task']['updated'] = count($yesterdayUpdatedTasks);
        $data['task']['total'] = \App\Task::count();
        $data['customer']['created'] = count($yesterdayCreatedCustomers);
        $data['customer']['updated'] = count($yesterdayUpdatedCustomers);
        $data['customer']['total'] = \App\Customer::count();
        $data['vendor']['created'] = count($yesterdayCreatedVendors);
        $data['vendor']['updated'] = count($yesterdayUpdatedVendors);
        $data['vendor']['total'] = \App\Vendor::count();

        return $data;
        /*
        \Mail::send('emails.AppSpecificReport', ['data' => $data, 'users' => $accessedUsers], function ($message) {
            $message->to($this->receivers)->subject($this->subject);
        });
         */
    }

    public function getMixpanelData()
    {
        /*
        $endpoint = array('export');
    
    //Create array of properties to send
    $parameters = array( 
    //'event' => 'Task', 
    'from_date' => '2016-07-10', 
    'to_date' => '2016-08-12'
    );
     
    //Make the request
    $data = $this->request($endpoint,$parameters);
    dd($data);

        $client = \MixGuzzle\MixGuzzleClient::factory(array(
            'api_key' => 'a0b4744909aafd1453435bc370e3b3c4',
            'api_secret' => '1c9f0fdc127f1fee383bdad211120923',
            'base_url' => '{scheme}://data.mixpanel.com/api/2.0/'
        ));
        $command = $client->getCommand('engage', array(
            // 'event' => 'Task',
            // 'from_date' => '2016-08-01',
            // 'to_date' => '2016-08-22',
            // 'type' => 'average'
            //'unit' => 'day',
            //'interval' => 10
            // 'limit' => 30
        ));
        dd(array_column($client->execute($command)['results'], '$distinct_id'));
        $command = $client->getCommand('events', array(
            'event' => array('Top', 'Page Viewed'),
            'type' => 'general',
            'unit' => 'day',
            'interval' => 10
        ));
        $response = $client->execute($command);
        dd($response);
        */
        // $mx = \Mixpanel::getInstance('de0eb9e9cd8bd129593dbfc20944dcc7', ["host" => "data.mixpanel.com"]);
        //$mp = new \Mixpanel('a0b4744909aafd1453435bc370e3b3c4', '1c9f0fdc127f1fee383bdad211120923');
        //$mp = new \Mixpanel('de0eb9e9cd8bd129593dbfc20944dcc7', ['segmentation', 'annotations']);
        // dd($mx);
        // dd($mx->request(['engage']));
        // dd($mx->identify('24d14184-f23e-48bd-ba87-f74f408dff36'));
        // dd($mx->request(['export'], ['from_date' => '2016-07-22 00:00:00', 'to_date' => '2016-08-22 00:00:00']));
    }

    public function request($methods, $params, $format='json') {
            // $end_point is an API end point such as events, properties, funnels, etc.
            // $method is an API method such as general, unique, average, etc.
            // $params is an associative array of parameters.
            // See https://mixpanel.com/docs/api-documentation/data-export-api
            
            if (count($params) < 1)
                return false;
            
            if (!isset($params['api_key']))
                $params['api_key'] = $this->api_key;
            
            $params['format'] = $format;
            
            if (!isset($params['expire'])) {
                $current_utc_time = time() - date('Z');
                $params['expire'] = $current_utc_time + 100000; // Default 10 minutes
            }
            
            $param_query = '';
            foreach ($params as $param => &$value) {
                if (is_array($value))
                    $value = json_encode($value);
                $param_query .= '&' . urlencode($param) . '=' . urlencode($value);
            }
            
            $sig = $this->signature($params);
            
            $uri = '/' . $this->version . '/' . join('/', $methods) . '/';
            $request_url = $uri . '?sig=' . $sig . $param_query;

            // $request_url = 'https://data.mixpanel.com/api/2.0/export/?from_date=2016-08-01&to_date=2016-08-22?sig='.$sig;
            
            $curl_handle=curl_init();
            curl_setopt($curl_handle,CURLOPT_URL,$this->api_url . $request_url);
            curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
            curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
            $data = curl_exec($curl_handle);
            curl_close($curl_handle);
                    
            return json_decode($data);
        }

    private function signature($params) {
            ksort($params);
            $param_string ='';
            foreach ($params as $param => $value) {
                $param_string .= $param . '=' . $value;
            }
            
            return md5($param_string . $this->api_secret);
        }

}
