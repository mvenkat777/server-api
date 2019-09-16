<?php

namespace Platform\SampleContainer\Mailer;

use Platform\App\Mailer\Mailer;

class SampleMailer extends Mailer
{
    public function sampleExport($user, $file, $data = [])
    {
        $view = 'exports.samples.mail';
        $subject = "Sample Export";

        return $this->sendWithAttachment($user, $subject, $view, $data, $file);
    }
}
