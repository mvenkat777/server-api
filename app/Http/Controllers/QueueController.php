<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use DB;
use Queue;
use Redis;

class QueueController extends Controller
{
    protected $v;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $value = Redis::lrange('queues:jobs:', 0, 1);
        // return $value;
        
        $jobs = Redis::lrange('queues:mediumJob', 0, -1);
        // $x = json_encode($jobs);
        // return $jobs;
        $queue = array();
        $mediumjobs = array();
        foreach ($jobs as $job) {
            $data = json_decode($job)->data->command;
            // $jobq = $this->getBetween('O:49:"', '":5', $data);
            // $techpack = $this->getBetween('s:36:"', '";', $data);
            // $user = $this->getBetween('s:23:"', '";', $data);
            // return $job;
            // $techpack = $this->getBetween('s:36:', ':8', $data)
            // $queue = array('job'=>$jobq, 'techpack'=>$techpack, 'user'=>$user, 'flag'=>1);
            $queue = array('job'=>$data);
            $mediumjobs[] = $queue;
            // echo '<br>';
        }
        
        $activity = Redis::lrange('queues:activity', 0, -1);
        // $x = json_encode($jobs);
        // return $jobs;
        $queue = array();
        $activityjobs = array();
        foreach ($activity as $activity) {
            $data = json_decode($activity)->data->command;
            // $activityq = $this->getBetween('O:36:"', '":25', $data);
            // return $job;
            // $techpack = $this->getBetween('s:36:', ':8', $data)
            $queue = array('job'=>$data);
            $activityjobs[] = $queue;
            // echo '<br>';
        }

        // return $y;
        $completed = Redis::lrange('completed', 0, -1);

        $complete = array();
        $completejobs = array();
        foreach ($completed as $complete) {
            $data = json_decode($complete)->data->command;
            // $job = $this->getBetween('O:49:"', '":5', $data);
            // $techpack = $this->getBetween('s:36:"', '";', $data);
            // $user = $this->getBetween('s:23:"', '";', $data);
            // return $job;
            // $techpack = $this->getBetween('s:36:', ':8', $data)
            // $complete = array('job'=>$job, 'techpack'=>$techpack, 'user'=>$user, 'flag'=>1);
            $complete = array('job'=>$data);
            $completejobs[] = $complete;
            // echo '<br>';
        }
         // return json_encode($completed);
        $failed = Redis::lrange('failed', 0, -1);

        $failq = array();
        $failjobs = array();
        foreach ($failed as $fail) {
            $data = json_decode($fail)->data->command;
            // $job = $this->getBetween('O:49:"', '":5', $data);
            // $techpack = $this->getBetween('s:36:"', '";', $data);
            // $user = $this->getBetween('s:23:"', '";', $data);
            // return $job;
            // $techpack = $this->getBetween('s:36:', ':8', $data)
            // $failq = array('job'=>$job, 'techpack'=>$techpack, 'user'=>$user, 'flag'=>1);
            $failq = array('job'=>$data);
            $failjobs[] = $failq;
            // echo '<br>';
        }

        return view('queueshow', ['medium'=>$mediumjobs, 'completed'=>$completejobs, 'failed'=>$failjobs]);
    }

    public function getBetween($from="",$to="",$string)
    {
        $temp = strpos($string,$from)+strlen($from);
        $result = substr($string,$temp,strlen($string));
        $dd=strpos($result,$to);
        if($dd == 0)
        {
            $dd = strlen($result);
        }

        return substr($result,0,$dd);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
