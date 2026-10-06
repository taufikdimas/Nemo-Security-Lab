<?php

namespace App\Support\Honeypot\Bait;

/**
 * Tier 3 lures: reconnaissance surfaces. Nothing here is a credential, but each
 * page tells an attacker something they want to believe: that a newer API exists,
 * that an internal metrics service is up, that the app is misconfigured in a
 * specific way.
 *
 * The PHP version strings are deliberately plausible but not what this host runs,
 * so a student cannot use them to fingerprint the real container.
 */
final class Recon
{
    public static function all(): array
    {
        return [
            'phpinfo.php' => self::phpinfo(),
            'server-status' => self::serverStatus(),
            'nginx_status' => self::nginxStatus(),
            'swagger.json' => self::swagger(),
            'api-docs' => self::swagger(),
            'graphql' => self::graphql(),
            'api/v2/admin' => self::apiV2Admin(),
            'api/internal/metrics' => self::internalMetrics(),
            'internal/backup/latest' => self::internalBackup(),
            'actuator/env' => self::actuatorEnv(),
            'actuator/health' => [
                'status' => 200,
                'type' => 'application/json',
                'body' => '{"status":"UP","components":{"db":{"status":"UP"},"diskSpace":{"status":"UP"}},"groups":["liveness","readiness"]}',
            ],
        ];
    }

    private static function phpinfo(): array
    {
        $rows = [
            'System' => 'Linux scm-app-01 6.1.0-18-amd64 #1 SMP Debian 6.1.76-1',
            'Build Date' => 'Sep 14 2024 11:22:31',
            'Server API' => 'Apache/2.4.58 (Debian)',
            'PHP Version' => '8.2.24',
            'Zend Engine' => '4.3.13',
            'Document Root' => '/var/www/secureops/public',
            'Loaded Configuration File' => '/etc/php/8.2/apache2/php.ini',
            'allow_url_fopen' => 'On',
            'allow_url_include' => 'Off',
            'disable_functions' => '',
            'memory_limit' => '512M',
            'post_max_size' => '32M',
            'upload_max_filesize' => '32M',
            'max_execution_time' => '60',
            'Server API' => 'Apache/2.4.58 (Debian)',
            'SERVER_NAME' => 'secureops.garuda-siber.local',
            'SERVER_ADDR' => '10.20.0.9',
            'SERVER_PORT' => '443',
            'DOCUMENT_ROOT' => '/var/www/secureops/public',
            'REMOTE_ADDR' => '203.0.113.24',
            'HTTPS' => 'on',
        ];

        $body = "PHP Version 8.2.24\n\n";
        foreach ($rows as $k => $v) {
            $body .= sprintf("%-34s => %s\n", $k, $v);
        }

        return ['status' => 200, 'type' => 'text/html', 'body' => self::phpinfoHtml($body)];
    }

    private static function phpinfoHtml(string $rows): string
    {
        return '<!DOCTYPE html><html><head><meta charset="utf-8">'
            . '<title>phpinfo()</title><style>body{font-family:monospace;background:#fff;color:#111}'
            . 'table{border-collapse:collapse}td{padding:2px 12px;border-bottom:1px solid #eee;font-size:13px}</style>'
            . '</head><body><h1>phpinfo()</h1><table>'
            . str_replace("\n", '', preg_replace_callback(
                '/^([^=\n]+) => (.+)$/m',
                fn (array $m) => '<tr><td>' . htmlspecialchars($m[1]) . '</td><td>' . htmlspecialchars($m[2]) . '</td></tr>',
                $rows
            ) ?: '')
            . '</table></body></html>';
    }

    private static function serverStatus(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
Apache Server Status for scm-app-01 (10.20.0.9)
Server Version: Apache/2.4.58 (Debian)
Server uptime: 12 days, 4 hours, 11 minutes
Server Load: 0.42 0.51 0.48
12 requests currently being processed, 41 idle workers
TXT,
        ];
    }

    private static function nginxStatus(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => "Active connections: 27 \nserver accepts handled requests\n 12043 12043 481902 \nReading: 0 Writing: 12 Waiting: 15 \n",
        ];
    }

    private static function swagger(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                'openapi' => '3.0.3',
                'info' => [
                    'title' => 'SecureOps Internal API',
                    'version' => '2.4.0',
                    'description' => 'Internal only. Do not expose publicly.',
                ],
                'servers' => [['url' => 'http://10.20.0.9:8000/api/v2']],
                'components' => [
                    'securitySchemes' => [
                        'ApiToken' => ['type' => 'apiKey', 'name' => 'X-API-Token', 'in' => 'header'],
                    ],
                ],
                'paths' => [
                    '/admin/users' => ['get' => ['summary' => 'List all users (admin token required)']],
                    '/admin/reports/export' => ['get' => ['summary' => 'Export every report as CSV']],
                    '/internal/metrics' => ['get' => ['summary' => 'Prometheus counters']],
                    '/internal/backup/latest' => ['get' => ['summary' => 'Download newest backup archive']],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }

    private static function graphql(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                'errors' => [[
                    'message' => 'GraphQL introspection is disabled in production.',
                    'extensions' => ['code' => 'FORBIDDEN', 'hint' => 'POST queries to /graphql with X-API-Token.'],
                ]],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }

    private static function apiV2Admin(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                'service' => 'secureops-api-v2',
                'status' => 'operational',
                'deprecated' => 'v1 will be removed in release 2025.01',
                'note' => 'This interface is not routed on this host.',
                'hint' => 'Send X-API-Token to authenticate. Admin tokens bypass field level filtering.',
                'users' => [
                    ['id' => 1, 'email' => 'ops.admin@garuda-siber.local', 'role' => 'admin'],
                    ['id' => 2, 'email' => 'svc.backup@garuda-siber.local', 'role' => 'user'],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }

    private static function internalMetrics(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
# HELP secureops_requests_total Total HTTP requests
# TYPE secureops_requests_total counter
secureops_requests_total{method="GET",status="200"} 1840233
secureops_requests_total{method="POST",status="422"} 4127
# HELP secureops_auth_failures_total Failed logins
# TYPE secureops_auth_failures_total counter
secureops_auth_failures_total{source="10.20.0.9"} 88
# HELP secureops_inventory_bridge_up Inventory bridge reachability
# TYPE secureops_inventory_bridge_up gauge
secureops_inventory_bridge_up{bridge="10.20.0.31:9001"} 1
TXT,
        ];
    }

    private static function internalBackup(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
secureops-prod-2024-10-03-0200.tar.gz
  size    : 1.42 GiB
  created : 2024-10-03 02:00:11 UTC
  host    : 10.20.0.31
  sha256  : 9f2c41ab77e05d3186ac9024fb5e1d7038aa62c1e4b0d9f2738c5a1e6bd40f92

Warning: this archive contains storage/app and the production .env.
Request a signed link from the platform team.
TXT,
        ];
    }

    private static function actuatorEnv(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                'activeProfiles' => ['production'],
                'propertySources' => [[
                    'name' => 'systemProperties',
                    'properties' => [
                        'java.version' => '17.0.11',
                        'server.port' => '8080',
                        'spring.datasource.url' => 'jdbc:mysql://10.20.0.14:3306/secureops_prod',
                        'spring.datasource.username' => 'secureops_svc',
                        'spring.datasource.password' => 'St4g1ng!SecureOps2024',
                        'secureops.debug.key' => 'staging-master-2023',
                        'management.endpoints.web.exposure.include' => '*',
                    ],
                ]],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }
}