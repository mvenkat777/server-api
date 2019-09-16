<?php

namespace Platform\Uploads\Helpers;

class UploadHelpers
{
	public static function generateUrl($name)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $link = preg_replace('/\./', '', substr(bcrypt($characters . time()), 0, 50));
        return $link;
    }
}