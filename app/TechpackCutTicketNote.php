<?php

namespace App;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class TechpackCutTicketNote extends Model
{
    // use ActivityRecorder;
	public $appName = 'techpack';
    protected $modelVerb = 'add';

	protected $table = 'techpack_cut_ticket_notes';
    protected $fillable = ['id', 'note', 'image', 'techpack_id'];
}