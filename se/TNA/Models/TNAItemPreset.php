<?php

namespace Platform\TNA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TNAItemPreset extends Model
{
    protected $table = 'tna_item_presets';

    public function departments()
    {
        return $this->hasOne('\Platform\TNA\Models\TNAItemDepartment', 'id');
    }
}
