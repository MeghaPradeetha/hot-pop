<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/store-video',
        '/save-preference',
        '/profile/images/delete/{id}',
        '/chat/read/message',
        '/chat/search',
        '/twilio-audio-call-laravel/audio-call',
        '/twilio-audio-call-laravel/audio-call/token',
        '/accept-event',
        '/caller-name',
        
        
    ];
}
