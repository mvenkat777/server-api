<?php

namespace Platform\Uploads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $table = 'uploads';

    /**
     * @var array
     */
    protected $fillable = ['name', 'title', 'self_link', 'web_link', 'is_public',
    						'mime_type', 'extension', 'description','web_link_sizes', 'size'];
}
