<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with project summary.
     */
    public function index(Request $request)
    {
        $query = Client::withCount('projects')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('has_projects')) {
            if ($request->has_projects === 'yes') {
                $query->has('projects');
            } elseif ($request->has_projects === 'no') {
                $query->doesntHave('projects');
            }
        }

        $clients = $query->paginate(5)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show form to create client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store new client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|email|unique:clients,email',
            'phone_number' => 'required|string|max:20',
        ]);

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display a specific client with projects.
     */
    public function show(Client $client)
    {
        $client->load('projects');

        return view('clients.show', compact('client'));
    }

    /**
     * Show form to edit client.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update client.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone_number' => 'required|string|max:20',
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Delete client only if no projects exist.
     */
    public function destroy(Client $client)
    {
        if ($client->projects()->exists()) {
            return redirect()
                ->route('clients.index')
                ->with('error', 'Cannot delete client with existing projects.');
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
