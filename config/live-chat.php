<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Polling Interval (milliseconds)
    |--------------------------------------------------------------------------
    |
    | How often the frontend polls for new messages when a chat is open.
    | Lower values feel more real-time but increase server load.
    |
    */

    'polling_interval' => env('LIVE_CHAT_POLLING_INTERVAL', 3000),

    /*
    |--------------------------------------------------------------------------
    | Max Message Length
    |--------------------------------------------------------------------------
    |
    | Maximum number of characters allowed in a single chat message.
    |
    */

    'max_message_length' => env('LIVE_CHAT_MAX_MESSAGE_LENGTH', 5000),

    /*
    |--------------------------------------------------------------------------
    | Typing Indicator Timeout (seconds)
    |--------------------------------------------------------------------------
    |
    | How long a user is considered "typing" after their last typing event.
    |
    */

    'typing_timeout' => env('LIVE_CHAT_TYPING_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Online Status Timeout (seconds)
    |--------------------------------------------------------------------------
    |
    | How long a user is considered "online" after their last activity.
    |
    */

    'online_timeout' => env('LIVE_CHAT_ONLINE_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Messages Per Page
    |--------------------------------------------------------------------------
    |
    | Number of messages loaded per page in chat history.
    |
    */

    'messages_per_page' => env('LIVE_CHAT_MESSAGES_PER_PAGE', 50),

    /*
    |--------------------------------------------------------------------------
    | Enable Typing Indicators
    |--------------------------------------------------------------------------
    */

    'enable_typing_indicators' => env('LIVE_CHAT_TYPING_INDICATORS', true),

    /*
    |--------------------------------------------------------------------------
    | Enable Online Status
    |--------------------------------------------------------------------------
    */

    'enable_online_status' => env('LIVE_CHAT_ONLINE_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The fully qualified class name of the User model.
    |
    */

    'user_model' => App\Models\User::class,

];
