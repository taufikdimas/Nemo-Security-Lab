<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        $legacy = config('services.legacy_backup', []);

        return view('admin.config', [
            'general' => [
                'Application' => config('app.name'),
                'Environment' => config('app.env'),
                'URL' => config('app.url'),
                'Locale' => config('app.locale'),
                'Timezone' => config('app.timezone'),
                'Debug Mode' => config('app.debug') ? 'enabled' : 'disabled',
            ],
            'infrastructure' => [
                'Database Connection' => config('database.default'),
                'Cache Store' => config('cache.default'),
                'Queue Connection' => config('queue.default'),
                'Session Driver' => config('session.driver'),
                'Filesystem Disk' => config('filesystems.default'),
            ],
            'maintenance' => [
                'Maintenance Script' => '/tmp/secureops-maintenance.sh',
                'Legacy Maintenance Script' => '/tmp/garuda-maintenance.sh',
                'Cron Definition' => '/etc/cron.d/garuda-siber',
                'Report Storage' => storage_path('reports'),
                'Log Storage' => storage_path('logs'),
            ],
            'integration' => [
                'Enabled' => ($legacy['enabled'] ?? false) ? 'yes' : 'no',
                'Endpoint' => $legacy['endpoint'] ?? '-',
                'Username' => $legacy['username'] ?? '-',
                'Secret' => isset($legacy['secret']) ? str_repeat('*', 8) : '-',
            ],
            'refreshedAt' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
