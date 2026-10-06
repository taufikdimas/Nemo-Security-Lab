<?php

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Incident;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Portal klien.
 *
 * CATATAN SCOPING — ini divergensi dari spesifikasi awal, dan disengaja:
 *
 * 1. Proyek di-scope dengan BOTH `Client.name` dan `Client.company`, bukan
 *    hanya `company`. Data di repo ini menyimpan `projects.client_name` sebagai
 *    "PT. Bank Mandiri Digital", yang persis sama dengan `clients.name`;
 *    sementara `clients.company` bernilai "Bank Mandiri Digital" (tanpa prefix).
 *    Kalau hanya memakai `company`, portal selalu mengembalikan 0 proyek.
 *
 * 2. Insiden di-scope lewat `assets.client_id`, bukan `assets.owner_department`.
 *    Nilai `owner_department` hanya department internal (NOC, SOC,
 *    IT Infrastructure, HR, Finance) dan tidak pernah memuat nama perusahaan,
 *    jadi tidak ada cara memfilter insiden per klien tanpa kolom baru.
 */
class ClientPortalController extends Controller
{
    /**
     * Klien yang terhubung ke akun yang sedang login.
     */
    private function getClient(): Client
    {
        $client = auth()->user()->clientProfile;

        abort_if(! $client, 403, 'Akun ini belum terhubung ke profil klien.');

        return $client;
    }

    /**
     * Batasi query proyek hanya milik klien ini.
     */
    private function projectScope(Builder $query, Client $client): Builder
    {
        return $query->where(function (Builder $inner) use ($client) {
            $inner->where('client_name', $client->name);

            if ($client->company && $client->company !== $client->name) {
                $inner->orWhere('client_name', $client->company);
            }
        });
    }

    /**
     * Batasi query insiden hanya yang menempel pada aset milik klien ini.
     */
    private function incidentScope(Builder $query, Client $client): Builder
    {
        return $query->whereHas('asset', function (Builder $asset) use ($client) {
            $asset->where('client_id', $client->id);
        });
    }

    /**
     * Pastikan proyek yang diminta benar-benar milik klien ini (anti-IDOR).
     */
    private function assertOwnedProject(Project $project, Client $client): void
    {
        $owned = $project->client_name === $client->name
            || ($client->company && $project->client_name === $client->company);

        abort_if(! $owned, 403, 'Proyek ini bukan milik akun Anda.');
    }

    public function dashboard()
    {
        $client = $this->getClient();

        $activeProjects = $this->projectScope(Project::query(), $client)
            ->where('status', 'active')
            ->latest()
            ->get();

        $completedProjects = $this->projectScope(Project::query(), $client)
            ->where('status', 'completed')
            ->count();

        $totalProjects = $this->projectScope(Project::query(), $client)->count();

        // "Terbuka" = masih open atau sedang ditangani, bukan yang sudah resolved.
        $openIncidents = $this->incidentScope(Incident::query(), $client)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $recentIncidents = $this->incidentScope(Incident::query(), $client)
            ->with('asset')
            ->latest()
            ->take(5)
            ->get();

        $recentProjects = $this->projectScope(Project::query(), $client)
            ->with('comments')
            ->latest()
            ->take(5)
            ->get();

        return view('portal.dashboard', compact(
            'client',
            'activeProjects',
            'completedProjects',
            'totalProjects',
            'openIncidents',
            'recentIncidents',
            'recentProjects'
        ));
    }

    public function projects(Request $request)
    {
        $client = $this->getClient();

        $query = $this->projectScope(Project::query(), $client)->with('comments');

        $status = $request->query('status');
        if ($status && in_array($status, ['active', 'completed', 'on_hold', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('portal.projects', compact('client', 'projects'));
    }

    public function showProject(Project $project)
    {
        $client = $this->getClient();

        $this->assertOwnedProject($project, $client);

        $project->load('comments.user');

        return view('portal.project-detail', compact('client', 'project'));
    }

    public function incidents(Request $request)
    {
        $client = $this->getClient();

        $query = $this->incidentScope(Incident::query(), $client)->with('asset');

        if ($status = $request->query('status')) {
            if (in_array($status, ['open', 'in_progress', 'resolved'], true)) {
                $query->where('status', $status);
            }
        }

        if ($priority = $request->query('priority')) {
            if (in_array($priority, ['critical', 'high', 'medium', 'low'], true)) {
                $query->where('priority', $priority);
            }
        }

        $incidents = $query->latest()->paginate(10)->withQueryString();

        return view('portal.incidents', compact('client', 'incidents'));
    }

    public function reports()
    {
        $client = $this->getClient();

        // Daftar metadata saja; isi & unduhan ditangani reportContent()
        // dan reportDownload(). Tetap hanya berkas .txt di direktori laporan.
        $reportFiles = collect(glob(storage_path('app/reports/*.txt')))
            ->map(fn ($path) => [
                'name' => basename($path),
                'size' => (int) filesize($path),
                'modified' => (int) filemtime($path),
            ])
            ->sortByDesc('modified')
            ->values();

        return view('portal.reports', compact('client', 'reportFiles'));
    }

    /**
     * Isi teks sebuah laporan, ditampilkan inline di portal.
     */
    public function reportContent(string $filename)
    {
        $client = $this->getClient();
        $path = $this->resolveReportPath($filename);

        // Batasi ukuran yang dirender supaya berkas raksasa di direktori
        // laporan tidak dipakai exhausting memory.
        if (filesize($path) > 2 * 1024 * 1024) {
            abort(404);
        }

        return view('portal.report-content', [
            'client' => $client,
            'name' => basename($path),
            'content' => file_get_contents($path),
            'size' => filesize($path),
            'modified' => (int) filemtime($path),
        ]);
    }

    /**
     * Unduh laporan sebagai text/plain.
     */
    public function reportDownload(string $filename)
    {
        $this->getClient();
        $path = $this->resolveReportPath($filename);

        return response()->download($path, basename($path), [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * Resolver path laporan yang aman.
     *
     * Nama berkas datang dari URL sehingga dicek berlapis:
     *   - basename() menolak segmen direktori ("../", "sub/x.txt")
     *   - ekstensi .txt menolak apa pun yang bukan laporan teks
     *   - realpath() containment menolak symlink yang keluar dari direktori
     */
    private function resolveReportPath(string $filename): string
    {
        $safe = basename($filename);

        if ($safe !== $filename || ! str_ends_with(strtolower($safe), '.txt')) {
            abort(404);
        }

        $dir = realpath(storage_path('app/reports'));
        $path = $dir === false ? false : realpath($dir.DIRECTORY_SEPARATOR.$safe);

        if ($dir === false || $path === false || ! str_starts_with($path, $dir.DIRECTORY_SEPARATOR)) {
            abort(404);
        }

        return $path;
    }

    public function pentest()
    {
        $client = $this->getClient();
        $engagements = \App\Models\PentestEngagement::where('client_id', $client->id)->latest()->get();

        return view('portal.pentest', compact('client', 'engagements'));
    }

    public function allFindings(Request $request)
    {
        $client = $this->getClient();
        $query = \App\Models\PentestFinding::whereHas('engagement', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })->with('engagement');

        if ($severity = $request->query('severity')) {
            if (in_array($severity, ['critical', 'high', 'medium', 'low', 'informational'], true)) {
                $query->where('severity', $severity);
            }
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('finding_id', 'like', "%{$search}%")
                  ->orWhere('affected_component', 'like', "%{$search}%");
            });
        }

        $findings = $query->latest()->paginate(15)->withQueryString();

        return view('portal.pentest-findings', compact('client', 'findings'));
    }

    public function showPentest(\App\Models\PentestEngagement $engagement)
    {
        $client = $this->getClient();
        abort_if($engagement->client_id !== $client->id, 403, 'Anda tidak memiliki akses ke engagement ini.');

        $engagement->load(['findings', 'reports' => fn($q) => $q->where('client_visible', true)]);

        return view('portal.pentest-detail', compact('client', 'engagement'));
    }

    public function pentestFindings(\App\Models\PentestEngagement $engagement)
    {
        $client = $this->getClient();
        abort_if($engagement->client_id !== $client->id, 403, 'Anda tidak memiliki akses ke engagement ini.');

        $findings = $engagement->findings()->latest()->paginate(15);

        return view('portal.pentest-findings', compact('client', 'engagement', 'findings'));
    }

    public function allPentestReports(Request $request)
    {
        $client = $this->getClient();
        $query = \App\Models\PentestReport::whereHas('engagement', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })->where('client_visible', true)->with('engagement');

        if ($type = $request->query('type')) {
            $query->where('report_type', $type);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('report_code', 'like', "%{$search}%");
            });
        }

        $reports = $query->latest()->paginate(12)->withQueryString();

        return view('portal.pentest-reports', compact('client', 'reports'));
    }

    public function pentestReports(\App\Models\PentestEngagement $engagement)
    {
        $client = $this->getClient();
        abort_if($engagement->client_id !== $client->id, 403, 'Anda tidak memiliki akses ke engagement ini.');

        $reports = $engagement->reports()->where('client_visible', true)->latest()->paginate(12);

        return view('portal.pentest-reports', compact('client', 'engagement', 'reports'));
    }

    public function showPentestReport(\App\Models\PentestReport $report)
    {
        $client = $this->getClient();
        abort_if($report->engagement->client_id !== $client->id || !$report->client_visible, 403, 'Laporan belum dipublikasikan untuk klien.');

        return view('portal.pentest-report-detail', compact('client', 'report'));
    }

    public function downloadPentestReport(\App\Models\PentestReport $report)
    {
        $client = $this->getClient();
        abort_if($report->engagement->client_id !== $client->id || !$report->client_visible, 403, 'Laporan belum dipublikasikan untuk klien.');

        if ($report->file_path && \Illuminate\Support\Facades\Storage::exists($report->file_path)) {
            return \Illuminate\Support\Facades\Storage::download($report->file_path, $report->title . '.pdf');
        }

        return back()->with('error', 'Berkas laporan tidak ditemukan di server.');
    }

    public function profile()
    {
        $client = $this->getClient();

        return view('portal.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Hanya name, phone, position, bio, password yang lolos validasi,
        // mencegah mass-assignment ke role/client_id/is_active.
        auth()->user()->update($validated);

        return back()->with('success', 'Profil dan informasi akun berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}