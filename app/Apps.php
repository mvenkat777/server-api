<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    protected $table = 'apps';

    protected $fillable = ['app_name'];

}
