<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Cross-platform audit trail for administrators: every logged action from
     * all operators, filterable by actor and action type.
     */
    public function activity(Request $request)
    {
        $activities = ActivityLog::with('user')
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->input('user_id')))
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->input('action')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->input('q');
                $q->where('details', 'LIKE', "%{$term}%");
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.activity', [
            'activities' => $activities,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'actions' => ActivityLog::query()
                ->distinct()
                ->orderBy('action')
                ->pluck('action'),
        ]);
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }
}