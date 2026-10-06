<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = Project::when($request->filled('search'), function ($q) use ($request) {
            $term = $request->input('search');
            $q->where(function ($w) use ($term) {
                $w->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%")
                    ->orWhere('client_name', 'LIKE', "%{$term}%");
            });
        })->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->input('status'));
        });

        if (! auth()->user()->isAdmin()) {
            $query->where(function ($q) use ($userId) {
                $q->where('created_by', $userId)
                  ->orWhereHas('members', fn ($m) => $m->where('users.id', $userId));
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,on_hold,cancelled',
        ]);

        $validated['created_by'] = auth()->id();
        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        abort_if(! $this->mayAccess($project), 403);

        // Eager-load the comment authors; the view renders $comment->user inside the
        // loop, which would otherwise issue one query per comment.
        $project->load(['comments.user', 'members']);
        $availableUsers = User::orderBy('name')->get();

        return view('projects.show', compact('project', 'availableUsers'));
    }

    public function storeComment(Request $request, Project $project)
    {
        abort_if(! $this->mayAccess($project), 403);

        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $comment = new ProjectComment();
        $comment->project_id = $project->id;
        $comment->user_id = auth()->id();
        $comment->comment = $validated['comment'];
        $comment->save();

        return back()->with('success', 'Comment added.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'status' => 'required|in:active,completed,on_hold,cancelled',
        ]);

        $project->update(['status' => $validated['status']]);

        return back()->with('success', 'Project status updated.');
    }

    public function storeMember(Request $request, Project $project)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // syncWithoutDetaching keeps the unique(project_id, user_id) index safe
        // when the operator re-submits a member who is already on the project.
        $project->members()->syncWithoutDetaching([$validated['user_id']]);

        return back()->with('success', 'Member added to project.');
    }

    public function destroyMember(Project $project, User $user)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $project->members()->detach($user->id);

        return back()->with('success', 'Member removed from project.');
    }

    public function edit(Project $project)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,on_hold,cancelled',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        abort_if($project->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }

    private function mayAccess(Project $project): bool
    {
        $user = auth()->user();
        if ($user->isAdmin() || $project->created_by === $user->id) {
            return true;
        }

        return $project->members()->where('users.id', $user->id)->exists();
    }
}