<?php

namespace Modules\CMS\App\Helpers;

class SessionHelper
{
    public static function pushToastNotification($message, $type = 'primary') {
        session()->flash('flash_message', $message);
        session()->flash('flash_type', $type);
    }
}
