<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class UserTag extends Model
{
    // use ActivityRecorder;

    protected $table = 'user_tag';

    protected $fillable = ['name'];

    protected $modelVerb = 'add';

    protected $appName = 'user';

    // public function getParentMeta()
    // {
    //     return [
    //         'id' => $this->users->id,
    //         'name'=>$this->users->display_name
    //     ];
    // }

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->note
        ];
    }


    public function users()
    {
        return $this->belongsToMany(
            'App\User',
            'user_user_tag',
            'tag_id',
            'user_id'
        )->withPivot('tagged_by');
    }
}
