<?php
namespace Platform\DirectMessage\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class Permission extends Eloquent {

	/**
     * defining database connection
     * @var connection_name
     */
    protected $connection = 'mongodb_dm';

    /*
     * defining collection name
     * @var collection
     */ 
    protected $collection = 'permission';

    /**
     * Mass Assignable fields
     * @var array
     */
    protected $fillable=['userId' ,'chatId' ,'members' ,'isGroup' ,'favourites' ,'shared', 'seen','seenList' ,'createdAt'];
}