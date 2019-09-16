<?php

namespace Platform\TNA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\TNA\Observers\TNAItemObserver;
use Platform\App\Activity\ActivityRecorder;

class TNAItem extends Model
{
    use SoftDeletes;

    use ActivityRecorder;

    protected $table = 'tna_items';

    public $appName = 'calender';

    public $incrementing = false;

    protected $fillable = [
        'id', 'title', 'description', 'tna_id', 'creator_id', 'task_days', 'planned_date', 
        'actual_date', 'representor_id', 'dependor_id', 'is_milestone', 
        'is_completed', 'is_dispatched', 'item_status_id', 'task_id',
        'is_parallel', 'label', 'projected_date', 'delta', 'is_priority_task',
        'department_id'
    ];

    protected $relation = [
        'creator_id' => 'creator|Platform\Users\Transformers\MetaUserTransformer', 
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer', 
        'order_id' => 'order|Platform\Orders\Transformers\MetaOrderTransformer', 
        'customer_id' => 'customer|Platform\Customer\Transformers\MetaCustomerTransformer', 
        'vendor_id' => 'vendor|Platform\Vendor\Transformers\MetaVendorTransformer', 
    ];

    protected $verbs = [
        'is_submitted' => 'submit|reject|boolean',
        'tna_health_id' => 'health|integer',
        'assignee_id' => 'reassign',
        'seen' => 'seen'
    ];

    protected $values = [
        'priority_id' => 'low|highest|intermediate',
        'status_id' => 'unassigned|assigned|started|submitted|completed'
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->title
        ];
    }

    public function getParentMeta()
    {
        return [
            'id' => $this->tna->id,
            'name'=>$this->tna->title
        ];
    }

    public function creator()
    {
    	return $this->belongsTo('App\User', 'creator_id');
    }

    public function tna()
    {
    	return $this->belongsTo('Platform\TNA\Models\TNA', 'tna_id');
    }

    public function representor()
    {
    	return $this->belongsTo('App\User', 'representor_id');
    }

    public function dependor()
    {
    	return $this->belongsTo('Platform\TNA\Models\TNAItem', 'dependor_id');
    }

    public function dependents()
    {
        return $this->hasMany('Platform\TNA\Models\TNAItem', 'dependor_id');
    }

    public function task()
    {
        return $this->belongsTo('App\Task', 'task_id', 'id');
    }

    public function itemStatus()
    {
        return $this->belongsTo('App\TaskStatus', 'item_status_id', 'id');
    }

    public function department()
    {
        return $this->hasOne('\Platform\TNA\Models\TNAItemDepartment', 'id', 'department_id');
    }

    public function visibility()
    {
        return $this->belongsToMany('Platform\TNA\Models\TNAItemVisibility', 
                                    'tna_item_visibility_tna_item', 
                                    'tna_item_id', 
                                    'tna_item_visibility_id')->withTimeStamps();
    }

    // public function update(array $attr)
    // {
    //     parent::update($attr);
    //     dd($attr);
    // }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new TNAItemObserver());
        parent::boot($events);
    }
}
