<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\Observers\SampleSubmission\SampleSubmissionCommentObserver;

class SampleSubmissionComment extends Model
{
    protected $table = 'sample_submission_comments';
    protected $fillable = [
        'id', 'sample_submission_id', 'sample_submission_categories_id', 'comment', 'commented_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'file' => 'object',
        'commented_by' => 'object',
    ];

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new SampleSubmissionCommentObserver);
        parent::boot($events);
    }
}
