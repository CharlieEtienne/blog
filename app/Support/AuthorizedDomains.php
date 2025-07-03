<?php

namespace App\Support;

class AuthorizedDomains
{
    public static function get(): array
    {
        return str(config('general.authorized_domains'))->remove(" ")->explode(",")->toArray();
    }

}
