<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\Observers\SampleSubmission\SampleSubmissionCategoryObserver;

class SampleSubmissionCategory extends Model
{
    protected $fillable = ['id', 'sample_submission_id', 'name', 'content'];

    public $incrementing = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * Comments relatiosnship for sample submission categories
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
		return $this->hasMany('App\SampleSubmissionComment', 'sample_submission_categories_id')
					->orderBy('updated_at', 'desc');
    }

    /**
     * Attachments relatiosnship for sample submission categories
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany('App\SampleSubmissionAttachment', 'sample_submission_categories_id');
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new SampleSubmissionCategoryObserver);
        parent::boot($events);
    }
}
