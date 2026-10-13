<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        return view('clients.index', [
            'clients' => Client::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('clients.create', [
            'client' => new Client,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('clients', 'public');
        }

        $client = Client::create($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Le client a bien été créé.');
    }

    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $previousPhoto = null;
        if ($request->hasFile('photo')) {
            $previousPhoto = $client->photo;
            $newPhoto = $request->file('photo')->store('clients', 'public');
            $validated['photo'] = $newPhoto;
        }

        $client->update($validated);

        if ($previousPhoto) {
            Storage::disk('public')->delete($previousPhoto);
        }

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Les informations du client ont bien été mises à jour.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        if ($client->photo) {
            Storage::disk('public')->delete($client->photo);
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Le client a bien été supprimé.');
    }
}
