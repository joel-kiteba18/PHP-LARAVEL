@extends('layouts.app')

@section('title', 'Commandes')
@section('breadcrumb', 'COMMANDES')
@section('page-title', 'Commandes')
@section('page-description', 'Consultez les commandes enregistrées et leurs bons de commande.')
@section('page-actions')
    <a class="button button-secondary" href="{{ route('commandes.sorties') }}">État des sorties</a>
    <a class="button button-primary" href="{{ route('commandes.create') }}">＋ Nouvelle commande</a>
@endsection

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Commandes enregistrées</span>
            <span class="stat-value">{{ number_format($commandes->total(), 0, ',', ' ') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Montant total des commandes</span>
            <span class="stat-value">{{ number_format((float) $totalCommandes, 2, ',', ' ') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Lignes par page</span>
            <span class="stat-value">10</span>
        </div>
    </div>

    <section class="card" aria-label="Liste des commandes">
        <div class="card-header">
            <h2 class="card-title">Historique des commandes</h2>
            <span class="field-hint">{{ $commandes->total() }} commande{{ $commandes->total() === 1 ? '' : 's' }}</span>
        </div>
        @if ($commandes->isEmpty())
            <div class="empty-state">
                <h2>Aucune commande enregistrée</h2>
                <p>Ajoutez des produits et des clients avant de créer une commande.</p>
                <a class="button button-primary" href="{{ route('commandes.create') }}">Créer une commande</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Nº</th>
                            <th scope="col">Date</th>
                            <th scope="col">Client</th>
                            <th scope="col">Produit</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Montant</th>
                            <th scope="col"><span class="sr-only">Bon de commande</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commandes as $commande)
                            <tr>
                                <td>#{{ $commande->id }}</td>
                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
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
                <div class="pagination-wrap">{{ $commandes->links('pagination.clients', ['label' => 'commandes']) }}</div>
            @endif
        @endif
    </section>
@endsection
