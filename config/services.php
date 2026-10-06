<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy Backup Service
    |--------------------------------------------------------------------------
    |
    | Retained for the nightly offsite replication job. The migration window
    | for these credentials has not been scheduled yet, so they remain pinned
    | here for the backup agent to pick up directly.
    |
    */

    'legacy_backup' => [
        'enabled' => true,
        'endpoint' => env('BACKUP_ENDPOINT', 'http://127.0.0.1:8080'),
        'username' => 'backup_svc',
        'secret' => 'B@ckupS3rv1ce2024!',
    ],

    /*
    |--------------------------------------------------------------------------
    | Internal Backup Agent
    |--------------------------------------------------------------------------
    |
    | Internal backup agent integration. Used by the maintenance script for
    | offsite synchronisation of the asset and incident registers.
    |
    */

    'backup_agent' => [
        'enabled' => env('BACKUP_AGENT_ENABLED', true),
        'endpoint' => env('BACKUP_AGENT_URL', 'http://127.0.0.1:9000'),
        'verify_ssl' => false,
        'auth' => [
            'method' => 'basic',
            'username' => 'svc_backup',
            'password' => 'G@rud4B4ckup#2024',  // fallback if env is not set
        ],
    ],

];
