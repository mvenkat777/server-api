<?php
namespace Platform\App\Activity\Models;

use Jenssegers\Mongodb\Model as Eloquent;
/**
* SubEntityNotification
*/
class SubEntityNotification extends Eloquent
{
	protected $collection = 'sub_entity_notification';

	protected $connection = 'mongodb_notification';
	
	protected $fillable = [
        'version', 'priority', 'entityId', 'actor', 'verb', 'meta', 'links','createdAt'
       ];
}