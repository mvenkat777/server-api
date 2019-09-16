<?php

namespace Platform\TNA\Models;

use Illuminate\Database\Eloquent\Model;

class TNATemplate extends Model
{
    protected $table = 'tna_templates';

    protected $fillable = [
        'title', 'description', 'creator_id', 'data', 'is_milestone_template', 'count'
    ];

    public function creator()
    {
    	return $this->belongsTo('App\User', 'creator_id');
    }
}

