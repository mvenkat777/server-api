<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CutTicketComment extends Model
{
    protected $fillable = [
        'id', 'techpack_id', 'comment', 'file', 'commented_by', 
    ];
}
