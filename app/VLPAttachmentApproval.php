<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VLPAttachmentApproval extends Model
{
    protected $fillable = [
        'id',
        'line_id',
        'approver_id',
        'approval'
    ];

    /**
     * A VLPAttachmentApproval has one approver
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function approver()
    {
        return $this->hasOne('App\User', 'id', 'approver_id');
    }
}
