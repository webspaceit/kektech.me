<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chat Module Configuration
    |--------------------------------------------------------------------------
    */

    // Enable/disable guest chat (public visitors)
    'guest_chat' => env('CHAT_GUEST_ENABLED', true),

    // Polling interval in milliseconds (frontend)
    'poll_interval' => env('CHAT_POLL_INTERVAL', 1000),

    // Max message length
    'max_message_length' => env('CHAT_MAX_MESSAGE_LENGTH', 5000),

    // Notification settings
    'notifications' => [
        'desktop' => env('CHAT_DESKTOP_NOTIFICATIONS', true),
        'sound' => env('CHAT_SOUND_NOTIFICATIONS', true),
    ],

    // Admin URL prefix
    'admin_prefix' => env('CHAT_ADMIN_PREFIX', 'wsdashboard'),
];
