<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProduitController extends Controller
{
    public function index(): View
    {
        return view('produits.index', [
            'produits' => Produits::query()->withCount('commandes')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('produits.create', [
            'produit' => new Produits,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ]);

        $produit = Produits::query()->create($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', "Le produit « {$produit->nom} » a bien été ajouté.");
    }

    public function show(Produits $produit): View
    {
        return view('produits.show', compact('produit'));
    }

    public function edit(Produits $produit): View
    {
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, Produits $produit): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ]);

        $produit->update($validated);

        return redirect()
            ->route('produits.show', $produit)
            ->with('success', 'Les informations du produit ont bien été mises à jour.');
    }

    public function destroy(Produits $produit): RedirectResponse
    {
        $deleted = DB::transaction(function () use ($produit): bool {
            $lockedProduct = Produits::query()
                ->lockForUpdate()
                ->findOrFail($produit->id);

            if ($lockedProduct->commandes()->exists()) {
                return false;
            }

            return (bool) $lockedProduct->delete();
        });

        if (! $deleted) {
            return redirect()
                ->route('produits.index')
                ->with('error', 'Ce produit ne peut pas être supprimé, car il est déjà associé à une ou plusieurs commandes.');
        }

        return redirect()
            ->route('produits.index')
            ->with('success', 'Le produit a bien été supprimé.');
    }
}
