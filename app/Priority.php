<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{

    protected $table = 'priorities';

    protected $fillable=['priority'];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    /**
     * @return Priority
     */
    public function getAllPriority()
    {
    	return $this->all();
    }
}