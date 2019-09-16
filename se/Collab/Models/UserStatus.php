<?php
namespace Platform\Collab\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class UserStatus extends Eloquent {

	/**
     * defining database connection
     * @var connection_name
     */
    protected $connection = 'mongodb_collab';

    /*
     * defining collection name
     * @var collection
     */ 
    protected $collection = 'user_status';

    /**
     * Mass Assignable fields
     * @var array
     */
    protected $fillable=['userId','lastUpdatedDateTime'];
}