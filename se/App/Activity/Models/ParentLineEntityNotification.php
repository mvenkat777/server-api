<?php
namespace Platform\App\Activity\Models;

use Jenssegers\Mongodb\Model as Eloquent;

/**
* ParentEntityNotification
*/
class ParentLineEntityNotification extends Eloquent
{
    protected $connection = 'mongodb_notification';
    
    protected $collection = 'entity_line_notification';
    
    protected $fillable = [
        'version','objectType', 'entityId', 'rules', 'entity', 'subEntity','updatedAt', 'lastSeen'
    ];
}