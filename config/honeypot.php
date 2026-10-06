<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Threat Capture
    |--------------------------------------------------------------------------
    |
    | Controls the passive monitoring of inbound requests for the edge
    | appliance. Requests that match a known scan signature are recorded for
    | review by the SOC team instead of being served.
    |
    */

    'enabled' => env('HONEYPOT_ENABLED', true),

    'user_agent_blocklist' => [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'dirbuster',
        'gobuster',
        'acunetix',
        'nessus',
        'zgrab',
    ],

    'decoy_paths' => [
        '.env',
        '.env.backup',
        '.git/config',
        '.git/head',
        '.svn/entries',
        '.ds_store',
        'backup.zip',
        'backup.sql',
        'db.sql',
        'database.sql',
        'dump.sql',
        'config/database.php.bak',
        'config/app.php.bak',
        '.aws/credentials',
        'vendor/phpunit/phpunit/src/util/php/eval-stdin.php',
        'wp-login.php',
        'xmlrpc.php',
        'phpmyadmin/',
        'server-status',
        'actuator/env',
        'api/v2/admin',
        'admin/login.php',
        'cgi-bin/test-cgi',
        'console',
        'debug',
        'trace.axd',
        'elmah.axd',
    ],
];
