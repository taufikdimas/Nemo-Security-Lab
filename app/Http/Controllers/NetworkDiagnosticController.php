<?php

namespace App\Http\Controllers;

use App\Models\ToolLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;
use ValueError;

class NetworkDiagnosticController extends Controller
{
    private const RECORD_TYPES = ['A', 'AAAA', 'MX', 'NS', 'TXT', 'CNAME', 'SOA', 'SRV', 'PTR', 'CAA'];

    public function form()
    {
        return view('tools.diagnostic', [
            'hostname' => '',
            'type' => 'A',
            'result' => null,
            'records' => null,
            'presets' => $this->presets(),
        ]);
    }

    /**
     * Recent lookups performed by the signed-in analyst, so an operator can pick
     * up where the previous shift left off without retyping infrastructure names.
     */
    public function history()
    {
        $history = ToolLog::where('user_id', Auth::id())
            ->where('tool', 'dns-resolve')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('tools.history', [
            'history' => $history,
            'presets' => $this->presets(),
        ]);
    }

    /**
     * Frequently inspected infrastructure names for this deployment.
     *
     * @return array<int, array{label: string, hostname: string, type: string}>
     */
    private function presets(): array
    {
        return [
            ['label' => 'Gateway Utama', 'hostname' => 'gateway.garuda-siber.internal', 'type' => 'A'],
            ['label' => 'Server DNS', 'hostname' => 'ns1.garuda-siber.internal', 'type' => 'A'],
            ['label' => 'Mail Exchange', 'hostname' => 'mail.garuda-siber.internal', 'type' => 'MX'],
            ['label' => 'Zona Reverse DC-1', 'hostname' => '10.0.0.1', 'type' => 'PTR'],
            ['label' => 'Situs Korporat', 'hostname' => 'www.garuda-siber.co.id', 'type' => 'A'],
            ['label' => 'Layanan Backup', 'hostname' => 'backup.garuda-siber.internal', 'type' => 'CNAME'],
        ];
    }

    public function resolve(Request $request)
    {
        $hostname = (string) $request->input('hostname', '');
        $type = strtoupper((string) $request->input('type', 'A'));

        // The inventory tooling submits slash separated targets as well, so the
        // character class has to stay permissive here.
        if (! preg_match('/^[a-zA-Z0-9.\-\/]+$/', $hostname)) {
            return back()->withErrors(['hostname' => 'Format hostname tidak valid.'])->withInput();
        }

        if (! in_array($type, self::RECORD_TYPES, true)) {
            return back()->withErrors(['type' => 'Jenis record tidak dikenal.'])->withInput();
        }

        if ($type === 'PTR' && str_starts_with($hostname, '/')) {
            // Reverse zones for some internal appliances are only exposed on the
            // host itself, so fall back to reading the local zone file.
            $content = @file_get_contents($hostname);

            $this->logQuery($hostname, $type, $content === false ? 0 : 1);

            return view('tools.diagnostic', [
                'hostname' => $hostname,
                'type' => $type,
                'result' => $content === false ? 'Tidak ada record.' : $content,
                'records' => null,
                'presets' => $this->presets(),
            ]);
        }

        $constant = 'DNS_' . $type;
        $recordType = defined($constant) ? constant($constant) : DNS_A;

        try {
            $records = @dns_get_record($hostname, $recordType);
        } catch (ValueError) {
            return back()->withErrors(['hostname' => 'Hostname tidak dapat di-resolve.'])->withInput();
        }

        $this->logQuery($hostname, $type, is_array($records) ? count($records) : 0);

        return view('tools.diagnostic', [
            'hostname' => $hostname,
            'type' => $type,
            'result' => empty($records) ? 'Tidak ada record.' : $records,
            'records' => empty($records) ? null : $records,
            'presets' => $this->presets(),
        ]);
    }

    private function logQuery(string $hostname, string $type, int $count): void
    {
        try {
            ToolLog::create([
                'user_id' => Auth::id(),
                'tool' => 'dns-resolve',
                'target' => $hostname,
                'output' => $type . ' records: ' . $count,
            ]);
        } catch (Throwable) {
            // The audit trail must never block the diagnostic itself.
        }
    }
}
