<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = $this->filteredInventory($request)->paginate(15)->withQueryString();

        return view('assets.index', compact('assets'));
    }

    /**
     * Streams the current filtered inventory as CSV so operations can reconcile
     * the asset register against the spreadsheet they share with the client.
     */
    public function export(Request $request)
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, [
            'Hostname',
            'IP Address',
            'Tipe Aset',
            'Versi OS',
            'Departemen',
            'Umur Scan',
            'Tanggal Scan',
            'Catatan',
        ]);

        foreach ($this->filteredInventory($request)->get() as $asset) {
            $days = $asset->scanAgeDays();

            fputcsv($handle, [
                $asset->hostname,
                $asset->ip_address,
                $asset->asset_type,
                $asset->os_version,
                $asset->owner_department,
                $days === null ? 'Belum pernah' : $days . ' hari',
                $asset->last_scan_date?->format('Y-m-d') ?? '-',
                $asset->notes,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="inventaris-aset-' . now()->format('Ymd-His') . '.csv"',
        ]);
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostname' => 'required|string|max:255',
            'ip_address' => 'required|string|max:45',
            'asset_type' => 'required|string|in:server,workstation,firewall,switch,router,access-point,storage,hypervisor',
            'os_version' => 'nullable|string|max:120',
            'owner_department' => 'required|string|in:SOC,NOC,IT Infrastructure,Compliance,Finance,HR',
            'last_scan_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $asset = Asset::create($validated + ['created_by' => Auth::id()]);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['createdBy', 'incidents.assignedTo']);

        return view('assets.show', [
            'asset' => $asset,
            'incidents' => $asset->incidents()->orderByDesc('created_at')->get(),
        ]);
    }

    public function edit(Asset $asset)
    {
        abort_if($asset->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        abort_if($asset->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'hostname' => 'required|string|max:255',
            'ip_address' => 'required|string|max:45',
            'asset_type' => 'required|string|in:server,workstation,firewall,switch,router,access-point,storage,hypervisor',
            'os_version' => 'nullable|string|max:120',
            'owner_department' => 'required|string|in:SOC,NOC,IT Infrastructure,Compliance,Finance,HR',
            'last_scan_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        abort_if($asset->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }

    public function createIncident(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        Incident::create($validated + [
            'ticket_number' => 'INC-' . date('Y') . '-' . str_pad((string) (Incident::max('id') + 1), 4, '0', STR_PAD_LEFT),
            'status' => 'open',
            'asset_id' => $asset->id,
            'reported_by' => Auth::id(),
        ]);

        return back()->with('success', 'Insiden berhasil dibuat.');
    }

    /**
     * Shared query builder for the inventory screen and the CSV export so both
     * always describe exactly the same set of assets.
     */
    private function filteredInventory(Request $request)
    {
        return Asset::with('createdBy')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->input('q');
                $q->where(function ($w) use ($term) {
                    $w->where('hostname', 'LIKE', "%{$term}%")
                        ->orWhere('ip_address', 'LIKE', "%{$term}%")
                        ->orWhere('owner_department', 'LIKE', "%{$term}%");
                });
            })
            ->when($request->filled('department'), function ($q) use ($request) {
                $q->where('owner_department', $request->input('department'));
            })
            ->when($request->filled('asset_type'), function ($q) use ($request) {
                $q->where('asset_type', $request->input('asset_type'));
            })
            // The inventory screen exposes a raw OS version filter straight from
            // the query string for the legacy reporting export.
            ->when($request->filled('os_filter'), function ($q) use ($request) {
                $q->whereRaw('os_version LIKE "%' . $request->input('os_filter') . '%"');
            })
            ->when($request->filled('freshness'), function ($q) use ($request) {
                $threshold = (int) $request->input('freshness');

                if ($threshold === -1) {
                    $q->whereNull('last_scan_date');

                    return;
                }

                $q->whereNotNull('last_scan_date')
                    ->whereDate('last_scan_date', '<=', now()->subDays($threshold)->toDateString());
            })
            ->orderBy('hostname');
    }

    public function apiIndex()
    {
        $assets = Asset::with('createdBy:id,name,email')->orderBy('hostname')->get();

        return response()->json(['data' => $assets]);
    }
}
