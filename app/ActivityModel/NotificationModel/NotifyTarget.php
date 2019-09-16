<?php

namespace App\ActivityModel\NotificationModel;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class NotifyTarget extends Eloquent
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
    protected $collection = 'notifyTo';

    /**
	 * Mass Assignable fields
	 * @var array
	 */
    protected $fillable=['localDate','action','actionObject','verb','target'];
}
