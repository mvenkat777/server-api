<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class org extends Model
{
    protected $table = 'orgs';

    protected $fillable = ['name','url','description','logo'];
}
