<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commandes;
use App\Models\Produits;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(): View
    {
        $commandes = Commandes::query()
            ->with(['client', 'produit'])
            ->latest()
            ->paginate(10);

        return view('commandes.index', [
            'commandes' => $commandes,
            'totalCommandes' => Commandes::query()->sum('total'),
        ]);
    }

    public function create(): View
    {
        return view('commandes.create', [
            'clients' => Client::query()->orderBy('name')->get(),
            'produits' => Produits::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'produit_id' => ['required', 'integer', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1', 'max:2147483647'],
        ]);

        $commande = DB::transaction(function () use ($validated): Commandes {
            $produit = Produits::query()
                ->lockForUpdate()
                ->findOrFail($validated['produit_id']);

            if ($validated['quantite'] > $produit->stock) {
                throw ValidationException::withMessages([
                    'quantite' => "Stock insuffisant : il reste {$produit->stock} unité(s) de ce produit.",
                ]);
            }

            $total = number_format((float) $produit->prix * $validated['quantite'], 2, '.', '');

            if ((float) $total > 99999999.99) {
                throw ValidationException::withMessages([
                    'quantite' => 'Le montant total dépasse la limite autorisée pour une commande.',
                ]);
            }

            $commande = Commandes::query()->create([
                'client_id' => $validated['client_id'],
                'produit_id' => $produit->id,
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $produit->prix,
                'total' => $total,
            ]);

            $produit->decrement('stock', $validated['quantite']);

            return $commande;
        });

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', 'La commande a été créée et la sortie du stock a été enregistrée.');
    }

    public function show(Commandes $commande): View
    {
        $commande->load(['client', 'produit']);

        return view('commandes.show', compact('commande'));
    }

    public function sorties(): View
    {
        $commandes = Commandes::query()
            ->with(['client', 'produit'])
            ->latest()
            ->paginate(15);

        return view('commandes.sorties', [
            'commandes' => $commandes,
            'totalQuantite' => Commandes::query()->sum('quantite'),
            'totalSorties' => Commandes::query()->sum('total'),
        ]);
    }
}
