<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Incident;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $projects = Project::where('client_name', $user->name)
                   ->orWhere('client_name', $user->company ?? '')
                   ->latest()->get();

        $totalProjects     = $projects->count();
        $activeProjects    = $projects->where('status', 'active')->count();
        $completedProjects = $projects->where('status', 'completed')->count();
        $openIncidents     = Incident::where('reported_by', $user->id)
                            ->whereIn('status', ['open', 'in_progress'])->count();

        return view('client.dashboard', compact(
            'projects', 'totalProjects', 'activeProjects',
            'completedProjects', 'openIncidents'
        ));
    }

    public function projects()
    {
        $user = auth()->user();
        $projects = Project::where('client_name', $user->name)
                   ->orWhere('client_name', $user->company ?? '')
                   ->latest()->paginate(10);
        return view('client.projects', compact('projects'));
    }

    public function projectShow(Project $project)
    {
        $user = auth()->user();
        abort_if(
            $project->client_name !== $user->name &&
            $project->client_name !== ($user->company ?? ''),
            403
        );
        return view('client.project-show', compact('project'));
    }

    public function incidents()
    {
        $incidents = Incident::where('reported_by', auth()->id())
                   ->latest()->paginate(10);
        return view('client.incidents', compact('incidents'));
    }

    public function reportIncident(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high,critical',
        ]);

        Incident::create([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => 'open',
            'reported_by' => auth()->id(),
        ]);

        return back()->with('success', 'Insiden berhasil dilaporkan. Tim kami akan segera menangani.');
    }
}
