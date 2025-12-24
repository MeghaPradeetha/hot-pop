<x-mail::message>
    Hello {{ $userName }},

    {{ $messageText }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
