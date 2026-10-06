<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::when($request->filled('search'), function ($q) use ($request) {
            $term = $request->input('search');
            $q->where(function ($w) use ($term) {
                $w->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%")
                    ->orWhere('company', 'LIKE', "%{$term}%");
            });
        });

        if (! auth()->user()->isAdmin()) {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhereNull('created_by');
            });
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $client = Client::create($validated);

        return redirect()->route('clients.show', $client)->with('success', 'Client created.');
    }

    public function show(Client $client)
    {
        abort_if($client->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        abort_if($client->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        abort_if($client->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        abort_if($client->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted.');
    }
}