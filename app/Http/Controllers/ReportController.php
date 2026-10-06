<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index', [
            'reports' => $this->availableReports(),
        ]);
    }

    /**
     * Renders the report generation form. Generating is a state-changing
     * action, so it only happens over POST.
     */
    public function generate()
    {
        return view('reports.generate', [
            'incidents' => Incident::with('asset')
                ->orderByDesc('id')
                ->get(['id', 'ticket_number', 'title']),
        ]);
    }

    /**
     * Accepts the report generation form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_id' => 'required|integer',
            'format' => 'required|string|max:10',
        ]);

        $incident = Incident::with(['asset', 'assignedTo', 'reportedBy'])
            ->find($validated['report_id']);

        if (! $incident) {
            return back()->withErrors(['report_id' => 'Data insiden tidak ditemukan.'])->withInput();
        }

        $directory = storage_path('app/reports');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $directory . '/' . $validated['report_id'] . '.' . $validated['format'];

        file_put_contents($filename, $this->render($incident));

        return redirect()->route('reports.index')
            ->with('success', 'Laporan ' . basename($filename) . ' berhasil dibuat.');
    }

    public function download(Request $request)
    {
        $reportId = $request->input('report_id');
        $format = $request->input('format', 'txt');

        // Report names follow the <id>.<format> convention shared with the
        // document management system.
        $filename = storage_path('app/reports/' . $reportId . '.' . $format);

        if (! file_exists($filename)) {
            abort(404, 'Laporan tidak ditemukan.');
        }

        return response()->download($filename);
    }

    private function availableReports(): array
    {
        $pattern = storage_path('app/reports') . '/*.txt';
        $files = glob($pattern) ?: [];

        $reports = [];

        foreach ($files as $path) {
            $reports[] = [
                'id' => basename($path, '.txt'),
                'filename' => basename($path),
                'size' => filesize($path),
                'modified' => date('Y-m-d H:i', filemtime($path)),
            ];
        }

        usort($reports, fn ($a, $b) => (int) $a['id'] <=> (int) $b['id']);

        return $reports;
    }

    private function render(Incident $incident): string
    {
        $lines = [
            'PT GARUDA SIBER NUSANTARA',
            'SecureOps Platform - Incident Report',
            str_repeat('=', 46),
            '',
            'Nomor Tiket  : ' . $incident->ticket_number,
            'Judul        : ' . $incident->title,
            'Prioritas    : ' . $incident->priority,
            'Status       : ' . $incident->status,
            'Aset         : ' . ($incident->asset?->hostname ?? '-'),
            'Pelapor      : ' . ($incident->reportedBy?->name ?? '-'),
            'Ditangani    : ' . ($incident->assignedTo?->name ?? '-'),
            'Dibuat       : ' . optional($incident->created_at)->format('Y-m-d H:i'),
            'Diselesaikan : ' . ($incident->resolved_at ? $incident->resolved_at->format('Y-m-d H:i') : '-'),
            '',
            'Deskripsi',
            str_repeat('-', 46),
            $incident->description ?: '-',
            '',
        ];

        return implode(PHP_EOL, $lines);
    }
}
