<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class TechpackUser extends Model
{
    protected $table = 'techpack_user';

    protected $fillable = ['techpack_id', 'user_id', 'permission'];
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;
}
