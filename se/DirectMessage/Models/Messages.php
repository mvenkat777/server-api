<?php
namespace Platform\DirectMessage\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class Messages extends Eloquent {

	/**
     * defining database connection
     * @var connection_name
     */
    protected $connection = 'mongodb_dm';

    /*
     * defining collection name
     * @var collection
     */ 
    protected $collection = 'messages';

    /**
     * Mass Assignable fields
     * @var array
     */
    protected $fillable=['chatId','messages','archived'];
}