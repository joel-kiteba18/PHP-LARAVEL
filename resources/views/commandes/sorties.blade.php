@extends('layouts.app')

@section('title', 'État des sorties')
@section('breadcrumb', 'ÉTAT DES SORTIES')
@section('page-title', 'État des sorties')
@section('page-description', 'Récapitulatif des produits sortis du stock lors des commandes.')
@section('page-actions')
    <a class="button button-secondary" href="{{ route('commandes.index') }}">← Retour aux commandes</a>
    <a class="button button-primary" href="{{ route('commandes.create') }}">＋ Nouvelle commande</a>
@endsection

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Commandes enregistrées</span>
            <span class="stat-value">{{ number_format($commandes->total(), 0, ',', ' ') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Unités sorties</span>
            <span class="stat-value">{{ number_format((int) $totalQuantite, 0, ',', ' ') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Valeur totale des sorties</span>
            <span class="stat-value">{{ number_format((float) $totalSorties, 2, ',', ' ') }}</span>
        </div>
    </div>

    <section class="card" aria-label="État des sorties de stock">
        <div class="card-header">
            <h2 class="card-title">Détail des sorties</h2>
            <span class="field-hint">Une ligne par commande</span>
        </div>
        @if ($commandes->isEmpty())
            <div class="empty-state">
                <h2>Aucune sortie de stock</h2>
                <p>Les commandes validées apparaîtront ici, avec leur quantité et leur bon de commande.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Commande</th>
                            <th scope="col">Client</th>
                            <th scope="col">Produit sorti</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Valeur</th>
                            <th scope="col"><span class="sr-only">Bon de commande</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commandes as $commande)
                            <tr>
                                <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                <td>#{{ $commande->id }}</td>
                                <td>{{ $commande->client->name }}</td>
                                <td>{{ $commande->produit->nom }}</td>
                                <td>{{ $commande->quantite }}</td>
                                <td>{{ number_format((float) $commande->total, 2, ',', ' ') }}</td>
                                <td><a class="button button-secondary button-small" href="{{ route('commandes.show', $commande) }}">Voir le bon</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($commandes->hasPages())
                <div class="pagination-wrap">{{ $commandes->links('pagination.clients', ['label' => 'sorties']) }}</div>
            @endif
        @endif
    </section>
@endsection
