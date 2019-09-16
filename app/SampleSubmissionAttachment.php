<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\Observers\SampleSubmission\SampleSubmissionAttachmentObserver;

class SampleSubmissionAttachment extends Model
{
    protected $fillable = ['sample_submission_id', 'sample_submission_categories_id', 'file', 'uploaded_by'];

     /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'file' => 'object',
        'uploaded_by' => 'object',
    ];

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new SampleSubmissionAttachmentObserver);
        parent::boot($events);
    }
}
