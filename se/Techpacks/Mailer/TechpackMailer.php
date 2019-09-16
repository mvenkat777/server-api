<?php

namespace Platform\Techpacks\Mailer;

use Platform\App\Mailer\Mailer;

class TechpackMailer extends Mailer
{
    public function techpackShared($user, $data)
    {
        $view = 'emails.techpacks.shared';
        $subject = $data['displayName'] . " has shared a techpack with you!";

        return $this->sendTo($user, $subject, $view, $data);
    }

    public function techpackExport($user, $file, $data = [])
    {
        $view = 'exports.techpacks.vendor.mail';
        $subject = "Techpack Export";

        return $this->sendWithAttachment($user, $subject, $view, $data, $file);
    }

    public function techpackMultipleExport($user, $file, $data = [])
    {
        $view = 'exports.techpacks.vendor.multipleMail';
        $subject = "Techpack Export";

        return $this->sendWithAttachment($user, $subject, $view, $data, $file);
    }

    public function techpackSharedWithNewUser($user, $data)
    {
        $view = 'emails.techpacks.sharedWithNewUser';
        $subject = $data['displayName'] . " has shared a techpack with you!";

        return $this->sendTo($user, $subject, $view, $data);
    }
}
