<?php

namespace Platform\CollabBoard\Mailer;

use Platform\App\Mailer\Mailer;

class CollabMailer extends Mailer
{
    public function invite($user, $data)
    {
        $view = 'emails.collabboards.invite';
        $subject = "Come collaborate on " . $data['customerName'] . " Collab boards.";

        return $this->sendTo($user, $subject, $view, $data);
    }
}
