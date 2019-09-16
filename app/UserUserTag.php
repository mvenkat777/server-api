<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class UserUserTag extends Model
{
    protected $table = 'user_user_tag';
    protected $fillable = ['tagged_by','user_id','tag_id'];
}
