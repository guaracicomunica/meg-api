<?php

namespace App\Utils;

class UniqueCode {
    public static function generate(): string
    {
        return uniqid();
    }
}
