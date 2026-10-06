<?php

namespace App\Http\Controllers;

use App\Models\HoneypotHit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class HoneypotController extends Controller
{
    private const CRITICAL_MARKERS = [
        '.env', '.git/config', 'config/app.php.bak', 'config/database.php.bak',
        '.aws/credentials', 'backup.zip', '.sql', 'eval-stdin.php',
    ];

    private const MEDIUM_MARKERS = [
        'wp-login.php', 'phpmyadmin', 'server-status', 'actuator/env',
        '.svn/', 'console', 'shell',
    ];

    public function decoy(Request $request)
    {
        $path = '/' . ltrim((string) ($request->route('any') ?? $request->path()), '/');
        $normalised = strtolower(preg_replace('#/+#', '/', $path) ?: $path);

        if ($this->isInteresting($normalised)) {
            $this->record($request, $normalised);
        }

        abort(404);
    }

    public function index(Request $request)
    {
        $hits = HoneypotHit::with('user:id,name,email')
            ->when($request->filled('severity'), function ($q) use ($request) {
                $q->where('severity', $request->input('severity'));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('path', 'LIKE', '%' . $request->input('q') . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $since = now()->subDay();

        $topPaths = HoneypotHit::select('path')
            ->selectRaw('count(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.threats', [
            'hits' => $hits,
            'severity' => (string) $request->input('severity', ''),
            'q' => (string) $request->input('q', ''),
            'topPaths' => $topPaths,
            'bySeverity' => HoneypotHit::select('severity')
                ->selectRaw('count(*) as total')
                ->groupBy('severity')
                ->get(),
            'stats' => [
                'total' => HoneypotHit::count(),
                'last24h' => HoneypotHit::where('created_at', '>=', $since)->count(),
                'sources' => HoneypotHit::distinct()->count('ip_address'),
            ],
        ]);
    }

    private function isInteresting(string $path): bool
    {
        foreach (config('honeypot.decoy_paths', []) as $decoy) {
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

    private function record(Request $request, string $path): void
    {
        try {
            $duplicate = HoneypotHit::where('path', $path)
                ->where('ip_address', $request->ip())
                ->where('method', $request->method())
                ->where('created_at', '>=', now()->subSeconds(60))
                ->exists();

            if ($duplicate) {
                return;
            }

            HoneypotHit::create([
                'path' => $path,
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
                'payload' => substr((string) $request->getQueryString(), 0, 2000),
                'user_id' => Auth::id(),
                'severity' => $this->severity($path),
            ]);
        } catch (Throwable) {
            // Recording must never change what the client receives.
        }
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
}
