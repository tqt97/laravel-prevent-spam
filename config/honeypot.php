<?php

return [
    'enabled' => env('HONEYPOT_ENABLED', true),

    'field_name' => env('HONEYPOT_NAME', 'my_name'),

    'field_time_name' => env('HONEYPOT_TIME', 'my_time'),

    'seconds' => env('HONEYPOT_SECONDS', 3),
];
