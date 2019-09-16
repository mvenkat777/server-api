<?php

namespace Platform\Observers;

class LineObserver
{
    public function updated($model)
    {
        if($model->getDirty('vlp_attachments')) {
            \App\VLPAttachmentApproval::where('line_id', $model->id)->delete();
        }
    }
}