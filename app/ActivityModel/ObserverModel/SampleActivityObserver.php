<?php

namespace App\ActivityModel\ObserverModel;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class SampleActivityObserver extends Eloquent
{
    /**
     * defining database connection
     * @var connection_name
     */
    protected $connection = 'mongodb';

    /*
     * defining collection name
     * @var collection
     */ 
    protected $collection = 'sampleActivityObserver';

    /**
     * Mass Assignable fields
     * @var array
     */
    protected $fillable=['localDate','actor','id','message'];
}