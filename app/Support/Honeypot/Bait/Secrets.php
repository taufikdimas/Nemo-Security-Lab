<?php

namespace App\Support\Honeypot\Bait;

/**
 * Tier 1 lures: leaked secrets. These are the paths a fuzzer surfaces first and
 * the ones an attacker is most motivated to chase, because the content looks like
 * it hands over credentials.
 *
 * Every value here is fabricated. The APP_KEY, debug key and API token are
 * deliberately different from the live ones so a student who follows this trail
 * cannot use it to skip a step of the chain, and cannot tell at a glance that
 * the trail is a dead end.
 */
final class Secrets
{
    public static function all(): array
    {
        return [
            '.env.backup' => self::envBackup(),
            '.env.old' => self::envOld(),
            '.env.production' => self::envProduction(),
            'config/database.php.bak' => self::databaseBak(),
            'config/app.php.bak' => self::appBak(),
            '.aws/credentials' => self::awsCredentials(),
            '.ssh/id_rsa' => self::sshKey(),
            'db.sql' => self::sqlDump(),
        ];
    }

    private static function envBackup(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
APP_NAME=SecureOps
APP_ENV=production
APP_KEY=base64:VEhJUy1JUy1OT1QtQS1SRUFMLUtFWS1CQUlULU9OTFktRE8tTk9ULVVTRS0wMDAw
APP_DEBUG=true
APP_URL=https://secureops.garuda-siber.local

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=10.20.0.14
DB_PORT=3306
DB_DATABASE=secureops_prod
DB_USERNAME=secureops_svc
DB_PASSWORD=St4g1ng!SecureOps2024

REDIS_HOST=10.20.0.21
REDIS_PASSWORD=r3d!sSecureOps99

MAIL_MAILER=smtp
MAIL_HOST=mail.garuda-siber.local
MAIL_PORT=587
MAIL_USERNAME=notifier@garuda-siber.local
MAIL_PASSWORD=N0t1fy!SecureOps

AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=ap-southeast-1

SECUREOPS_DEBUG_KEY=staging-master-2023
INVENTORY_API_TOKEN=4f9c1a77b2e840d6a3c95e17b0d4826f
SESSION_LIFETIME=120
TXT,
        ];
    }

    private static function envOld(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
# stale copy - do not use
APP_ENV=staging
APP_KEY=base64:VEhJUy1JUy1OT1QtQS1SRUFMLUtFWS1CQUlULU9OTFktRE8tTk9ULVVTRS0wMDAw
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=10.20.3.44
DB_DATABASE=secureops_staging
DB_USERNAME=secureops_dev
DB_PASSWORD=D3vStag3!SecureOps

SECUREOPS_DEBUG_KEY=staging-master-2023
QUEUE_CONNECTION=sync
CACHE_DRIVER=file
TXT,
        ];
    }

    private static function envProduction(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
APP_NAME=SecureOps
APP_ENV=production
APP_KEY=base64:VEhJUy1JUy1OT1QtQS1SRUFMLUtFWS1CQUlULU9OTFktRE8tTk9ULVVTRS0wMDAw
APP_DEBUG=false
APP_URL=https://secureops.garuda-siber.local

DB_CONNECTION=mysql
DB_HOST=10.20.0.14
DB_PORT=3306
DB_DATABASE=secureops_prod
DB_USERNAME=secureops_svc
DB_PASSWORD=St4g1ng!SecureOps2024

SECUREOPS_DEBUG_KEY=staging-master-2023
INVENTORY_API_TOKEN=4f9c1a77b2e840d6a3c95e17b0d4826f
TXT,
        ];
    }

    private static function databaseBak(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '10.20.0.14'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'secureops_prod'),
            'username' => env('DB_USERNAME', 'secureops_svc'),
            'password' => env('DB_PASSWORD', 'St4g1ng!SecureOps2024'),
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_PGSQL_HOST', '10.20.0.15'),
            'database' => env('DB_PGSQL_DATABASE', 'secureops_analytics'),
            'username' => env('DB_PGSQL_USERNAME', 'analytics_ro'),
            'password' => env('DB_PGSQL_PASSWORD', 'An4lytics!ReadOnly'),
        ],
    ],
];
TXT,
        ];
    }

    private static function appBak(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
<?php

return [
    'name' => env('APP_NAME', 'SecureOps'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'https://secureops.garuda-siber.local'),

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    // Inventory bridge shares a key with the reporting service.
    'inventory' => [
        'bridge_url' => env('INVENTORY_BRIDGE_URL', 'http://10.20.0.31:9001'),
        'bridge_token' => env('INVENTORY_API_TOKEN', '4f9c1a77b2e840d6a3c95e17b0d4826f'),
    ],

    'debug_master_key' => env('SECUREOPS_DEBUG_KEY', 'staging-master-2023'),
];
TXT,
        ];
    }

    private static function awsCredentials(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
[default]
aws_access_key_id = AKIAIOSFODNN7EXAMPLE
aws_secret_access_key = wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
region = ap-southeast-1

[inventory-backup]
aws_access_key_id = AKIAI44QH8DHBEXAMPLE
aws_secret_access_key = je7MtGbClwBF/2Zp9Utk/h3yCo8nvbEXAMPLEKEY
region = ap-southeast-1
TXT,
        ];
    }

    private static function sshKey(): array
    {
        return [
            'status' => 200,
            'type' => 'application/x-pem-file',
            'body' => <<<'TXT'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBAJ8vQ2hpY2hlbi1rZXktbWF0ZXJpYWwtZm9yLWRlbW8tb25seS1iYWl0
MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAAAJgkr3a2Xk6
ZXhhbXBsZS1ub3QtcmVhbC1rZXktbWF0ZXJpYWwtZm9yLWRlbW8tb25seS1iYWl0MDAw
MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAAAJHNvbG9uZ2Vy
LWJhaXQtZXhhbXBsZS1ub3QtcmVhbC1rZXktbWF0ZXJpYWwtZm9yLWRlbW8tb25seS1i
YWl0MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMCAAJHRoaXNpcy
Bpcy1ub3QtYS1yZWFsLXByaXZhdGUta2V5LWJhaXQtbWF0ZXJpYWwtZm9yLWRlbW8tb25s
eS1iYWl0MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDA=
-----END OPENSSH PRIVATE KEY-----
TXT,
        ];
    }

    private static function sqlDump(): array
    {
        return [
            'status' => 200,
            'type' => 'application/sql',
            'body' => <<<'TXT'
-- SecureOps schema dump (fabricated lure)
SET NAMES utf8mb4;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_token` varchar(64) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES
(1,'Rina Hartono','ops.admin@garuda-siber.local','$2y$10$FAKEHASHFAKEHASHFAKEHASHFAKEH','c41a77b2e840d6a3c95e17b0d4826f1','admin','2024-02-11 03:20:00'),
(2,'Bayu Prasetyo','svc.backup@garuda-siber.local','$2y$10$FAKEHASHFAKEHASHFAKEHASHFAKEH','7d2e0c9a5b1f46d3ae82c7019fb35d64','user','2024-03-02 07:41:00');
TXT,
        ];
    }
}