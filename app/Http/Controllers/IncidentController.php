<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $incidents = Incident::with(['asset', 'assignees', 'assignedTo', 'reportedBy'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->input('q');
                $q->where(function ($w) use ($term) {
                    $w->where('ticket_number', 'LIKE', "%{$term}%")
                        ->orWhere('title', 'LIKE', "%{$term}%");
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            })
            ->when($request->filled('priority'), function ($q) use ($request) {
                $q->where('priority', $request->input('priority'));
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('incidents.index', compact('incidents'));
    }

    public function create()
    {
        return view('incidents.create', [
            'assets' => Asset::orderBy('hostname')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,resolved',
            'asset_id' => 'required|exists:assets,id',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => 'required|exists:users,id',
        ], [
            'assigned_to.required' => 'Setidaknya satu analis (PIC) wajib ditugaskan untuk menangani insiden ini.',
            'assigned_to.min' => 'Setidaknya satu analis (PIC) wajib ditugaskan untuk menangani insiden ini.',
        ]);

        $assigneeIds = (array) $request->input('assigned_to', []);
        $assigneeIds = array_filter(array_map('intval', $assigneeIds));

        $validated['ticket_number'] = 'INC-' . date('Y') . '-'
            . str_pad((string) (Incident::max('id') + 1), 4, '0', STR_PAD_LEFT);
        $validated['reported_by'] = Auth::id();
        $validated['resolved_at'] = $validated['status'] === 'resolved' ? now() : null;
        $validated['assigned_to'] = !empty($assigneeIds) ? reset($assigneeIds) : null;

        $incident = Incident::create($validated);
        if (!empty($assigneeIds)) {
            $incident->assignees()->sync($assigneeIds);
        }

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Insiden berhasil dibuat.');
    }

    public function show(Incident $incident)
    {
        $incident->load(['asset', 'assignees', 'assignedTo', 'reportedBy', 'notes.author']);

        return view('incidents.show', [
            'incident' => $incident,
            'assets' => Asset::orderBy('hostname')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function edit(Incident $incident)
    {
        $incident->load('assignees');

        return view('incidents.edit', [
            'incident' => $incident,
            'assets' => Asset::orderBy('hostname')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,resolved',
            'asset_id' => 'required|exists:assets,id',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => 'required|exists:users,id',
        ], [
            'assigned_to.required' => 'Setidaknya satu analis (PIC) wajib ditugaskan untuk menangani insiden ini.',
            'assigned_to.min' => 'Setidaknya satu analis (PIC) wajib ditugaskan untuk menangani insiden ini.',
        ]);

        $assigneeIds = (array) $request->input('assigned_to', []);
        $assigneeIds = array_filter(array_map('intval', $assigneeIds));

        $validated['resolved_at'] = $validated['status'] === 'resolved'
            ? ($incident->resolved_at ?? now())
            : null;
        $validated['assigned_to'] = !empty($assigneeIds) ? reset($assigneeIds) : null;

        $incident->update($validated);
        $incident->assignees()->sync($assigneeIds);

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Insiden berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $incident->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved'
                ? ($incident->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', 'Status insiden diperbarui.');
    }

    public function storeNote(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        $incident->notes()->create([
            'user_id' => auth()->id(),
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Catatan insiden berhasil ditambahkan.');
    }

    public function assign(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable',
            'assigned_to.*' => 'exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'action' => 'nullable|in:sync,add,remove',
        ]);

        $action = $request->input('action', 'sync');

        if ($action === 'add' && $request->filled('user_id')) {
            $incident->assignees()->syncWithoutDetaching([$request->input('user_id')]);
        } elseif ($action === 'remove' && $request->filled('user_id')) {
            $incident->assignees()->detach($request->input('user_id'));
        } else {
            $assigneeIds = (array) $request->input('assigned_to', []);
            $assigneeIds = array_filter(array_map('intval', $assigneeIds));
            $incident->assignees()->sync($assigneeIds);
        }

        // Sync first assignee to assigned_to column for backward compatibility
        $firstAssignee = $incident->assignees()->first();
        $incident->update(['assigned_to' => $firstAssignee?->id]);

        return back()->with('success', 'Penugasan analis insiden berhasil diperbarui.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Insiden berhasil dihapus.');
    }

    /**
     * Compact incident feed for internal integrators.
     */
    public function apiIndex()
    {
        $incidents = Incident::with(['asset:id,hostname', 'assignees:id,name', 'assignedTo:id,name'])
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $incidents]);
    }
}
