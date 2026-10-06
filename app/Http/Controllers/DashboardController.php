<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\File;
use App\Models\Incident;
use App\Models\Project;
use App\Models\VulnDb;

class DashboardController extends Controller
{
    /** Aset yang tidak dipindau dalam rentang ini dilaporkan sebagai offline. */
    private const STALE_SCAN_HOURS = 168;

    public function index()
    {
        // Akun portal klien tidak punya dashboard internal. Dipindahkan ke
        // /portal/dashboard, bukan ditolak, supaya login tetap terlihat mulus.
        if (auth()->user()->isClient()) {
            return redirect()->route('portal.dashboard');
        }

        $user = auth()->user();

        // Statistik personal pengguna
        $totalFiles = File::where('uploaded_by', $user->id)->count();
        $totalActivities = ActivityLog::where('user_id', $user->id)->count();

        // File terbaru
        $recentFiles = File::where('uploaded_by', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Aktivitas terbaru (seluruh pengguna, untuk tampilan SOC)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        // --- Ringkasan SOC ---------------------------------------------------
        $openIncidents = Incident::where('status', 'open')->count();
        $criticalIncidents = Incident::where('priority', 'critical')->where('status', 'open')->count();
        $highIncidents = Incident::where('priority', 'high')->where('status', 'open')->count();
        $newIncidentsToday = Incident::whereDate('created_at', today())->count();

        $recentIncidents = Incident::with('asset:id,hostname')
            ->latest()
            ->take(7)
            ->get();

        $totalAssets = Asset::count();
        $offlineAssets = Asset::where('last_scan_date', '<', now()->subHours(self::STALE_SCAN_HOURS))->count();
        $onlineAssets = $totalAssets - $offlineAssets;

        $assetsByType = Asset::query()
            ->pluck('asset_type')
            ->countBy()
            ->sortDesc();

        // Department diturunkan lewat relasi incident -> asset karena tabel
        // incidents tidak memiliki kolom department.
        $incidentsByDept = Incident::with('asset:id,owner_department')
            ->get(['id', 'status', 'asset_id'])
            ->groupBy(fn (Incident $incident) => $incident->asset?->owner_department ?? 'Tidak Ditentukan')
            ->map(fn ($group, $department) => [
                'department' => $department,
                'open' => $group->where('status', 'open')->count(),
                'in_progress' => $group->where('status', 'in_progress')->count(),
                'resolved' => $group->where('status', 'resolved')->count(),
            ])
            ->sortByDesc('open')
            ->values();

        $activeProjects = Project::where('status', 'active')->count();
        $completedProjects = Project::where('status', 'completed')->count();

        $totalVulns = VulnDb::count();
        $criticalVulns = VulnDb::where('severity', 'critical')->count();

        return view('dashboard', compact(
            'totalFiles',
            'totalActivities',
            'recentFiles',
            'recentActivities',
            'openIncidents',
            'criticalIncidents',
            'highIncidents',
            'newIncidentsToday',
            'recentIncidents',
            'totalAssets',
            'onlineAssets',
            'offlineAssets',
            'assetsByType',
            'incidentsByDept',
            'activeProjects',
            'completedProjects',
            'totalVulns',
            'criticalVulns'
        ));
    }
}
