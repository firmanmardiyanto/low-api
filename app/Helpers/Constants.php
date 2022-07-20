<?php

namespace App\Helpers;

class Constants {
    static function getLoginRedirectUrl() {
        return env('LOGIN_REDIRECT_URL');
    }
}