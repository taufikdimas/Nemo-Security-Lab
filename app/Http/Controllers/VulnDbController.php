<?php

namespace App\Http\Controllers;

use App\Models\VulnDb;
use Illuminate\Http\Request;

class VulnDbController extends Controller
{
    public function index(Request $request)
    {
        $vulns = VulnDb::when($request->filled('search'), function ($q) use ($request) {
            $term = $request->input('search');
            $q->where(function ($w) use ($term) {
                $w->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('cve_id', 'LIKE', "%{$term}%")
                    ->orWhere('category', 'LIKE', "%{$term}%")
                    ->orWhere('affected_systems', 'LIKE', "%{$term}%");
            });
        })
        ->when($request->filled('severity'), function ($q) use ($request) {
            $q->where('severity', $request->input('severity'));
        })
        ->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', $request->input('category'));
        })
        ->latest()->paginate(10)->withQueryString();

        $categories = VulnDb::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('vulndb.index', compact('vulns', 'categories'));
    }

    public function create()
    {
        return view('vulndb.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cve_id' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
            'cvss_score' => 'nullable|numeric|min:0|max:10',
            'category' => 'nullable|string|max:100',
            'affected_systems' => 'nullable|string',
            'published_year' => 'nullable|string|size:4',
            'remediation' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $vuln = VulnDb::create($validated);

        return redirect()->route('vulndb.show', $vuln)->with('success', 'Vulnerability added.');
    }

    public function show(VulnDb $vuln)
    {
        return view('vulndb.show', compact('vuln'));
    }

    public function edit(VulnDb $vuln)
    {
        abort_if($vuln->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('vulndb.edit', compact('vuln'));
    }

    public function update(Request $request, VulnDb $vuln)
    {
        abort_if($vuln->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'cve_id' => 'nullable|string|max:32',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
            'cvss_score' => 'nullable|numeric|min:0|max:10',
            'category' => 'nullable|string|max:100',
            'affected_systems' => 'nullable|string',
            'published_year' => 'nullable|string|size:4',
            'remediation' => 'nullable|string',
        ]);

        $vuln->update($validated);

        return redirect()->route('vulndb.show', $vuln)->with('success', 'Kerentanan berhasil diperbarui.');
    }

    public function destroy(VulnDb $vuln)
    {
        abort_if($vuln->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $vuln->delete();
        return redirect()->route('vulndb.index')->with('success', 'Vulnerability deleted.');
    }
}