<?php
namespace app;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table = 'permissions';

    protected $fillable=['permission'];

    public $timestamps = false;

}