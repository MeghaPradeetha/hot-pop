<?php

namespace App\Entities\PushNotifications;

use Illuminate\Database\Eloquent\Model;

class SupportData extends Model
{
    public function getExtraApiFields()
    {
        return [
            "interests" => ['type' => 'array', 'items' => ['type' => 'string']],
            "nationality" => ['type' => 'array', 'items' => ['type' => 'string']]
        ];
    }
}