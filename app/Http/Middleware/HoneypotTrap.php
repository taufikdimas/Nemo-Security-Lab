<?php

namespace App\Http\Middleware;

use App\Models\HoneypotHit;
use App\Support\Honeypot\Lures;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HoneypotTrap
{
    /** Request fields that are never copied into the recorded payload. */
    private const REDACTED_FIELDS = [
        'password',
        'password_confirmation',
        'token',
        'api_token',
        'secret',
    ];

    private const CRITICAL_MARKERS = [
        '.env',
        '.git/config',
        'config/app.php.bak',
        'config/database.php.bak',
        '.aws/credentials',
        'backup.zip',
        '.sql',
    ];

    private const MEDIUM_MARKERS = [
        'wp-login.php',
        'phpmyadmin',
        'server-status',
        '.svn/',
        'actuator/env',
        'console',
        'debug',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (config('honeypot.enabled')) {
            $path = $this->normalise($request->path());

            $lure = Lures::for($path, $request->isMethod('post'));

            if ($lure !== null) {
                $this->record($request, $path, $request->isMethod('post') ? $this->payload($request) : 'lure-served');

                return response($lure['body'], $lure['status'], [
                    'Content-Type' => $lure['type'],
                ]);
            }

            if ($this->isInteresting($path)) {
                $this->record($request, $path);

                abort(404);
            }

            if ($this->isScanner($request)) {
                $this->record($request, $path, $request->userAgent() ?? 'scanner');

                abort(404);
            }
        }

        return $next($request);
    }

    private function normalise(string $path): string
    {
        $path = strtolower(trim($path));
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        return $path;
    }

    private function isInteresting(string $path): bool
    {
        $decoys = config('honeypot.decoy_paths', []);

        foreach ($decoys as $decoy) {
            if (str_contains($path, strtolower((string) $decoy))) {
                return true;
            }
        }

        foreach (['../', '..\\', '%2e%2e'] as $traversal) {
            if (str_contains($path, $traversal)) {
                return true;
            }
        }

        return false;
    }

    private function isScanner(Request $request): bool
    {
        $agent = strtolower((string) $request->userAgent());

        foreach (config('honeypot.user_agent_blocklist', []) as $needle) {
            if ($agent !== '' && str_contains($agent, strtolower((string) $needle))) {
                return true;
            }
        }

        return false;
    }

    private function severity(string $path): string
    {
        foreach (self::CRITICAL_MARKERS as $marker) {
            if (str_contains($path, $marker)) {
                return 'critical';
            }
        }

        foreach (self::MEDIUM_MARKERS as $marker) {
            if (str_contains($path, $marker)) {
                return 'medium';
            }
        }

        return 'low';
    }

    private function record(Request $request, string $path, ?string $payload = null): void
    {
        try {
            HoneypotHit::create([
                'path' => '/' . ltrim($path, '/'),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
                'payload' => $payload ?? $this->payload($request),
                'user_id' => Auth::id(),
                'severity' => $this->severity($path),
            ]);
        } catch (Throwable) {
            // Logging must never interfere with the response the client receives.
        }
    }

    private function payload(Request $request): string
    {
        $query = $request->getQueryString();

        if (is_string($query) && $query !== '') {
            return substr($query, 0, 2000);
        }

        $body = $request->request->all();

        foreach (self::REDACTED_FIELDS as $field) {
            unset($body[$field]);
        }

        return substr(json_encode($body, JSON_UNESCAPED_UNICODE) ?: '', 0, 2000);
    }
}
