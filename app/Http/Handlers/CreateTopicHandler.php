<?php

namespace App\Http\Handlers;

use App\Models\Topic;

class CreateTopicHandler
{
    public static function handle(array $data)
    {
        Topic::create($data);
    }
}
