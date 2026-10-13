@extends('layouts.app')

@section('title', 'Bon de commande #' . $commande->id)
@section('breadcrumb', 'BON DE COMMANDE')
@section('page-title', 'Bon de commande')
@section('page-description', 'Document récapitulatif de la commande et de la sortie de stock.')
@section('page-actions')
    <a class="button button-secondary screen-only" href="{{ route('commandes.index') }}">← Retour aux commandes</a>
    <button class="button button-primary screen-only" type="button" onclick="window.print()">Imprimer le bon</button>
@endsection

@section('content')
    <section class="card document-card">
        <div class="card-body">
            <div class="document-header">
                <div>
                    <p class="document-number">BON DE COMMANDE Nº {{ str_pad((string) $commande->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <h2 class="card-title">Client Management</h2>
                </div>
                <div>
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="detail-list document-client">
                <div>
                    <span class="detail-label">Client</span>
                    <span class="detail-value">{{ $commande->client->name }}</span>
                </div>
                <div>
                    <span class="detail-label">Téléphone</span>
                    <span class="detail-value">{{ $commande->client->phone }}</span>
                </div>
            </div>

            <div class="table-wrap">
                <table class="document-lines">
                    <thead>
                        <tr>
                            <th scope="col">Désignation</th>
                            <th scope="col">Prix unitaire</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $commande->produit->nom }}</td>
                            <td>{{ number_format((float) $commande->prix_unitaire, 2, ',', ' ') }}</td>
                            <td>{{ $commande->quantite }}</td>
                            <td>{{ number_format((float) $commande->total, 2, ',', ' ') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="field-hint">Sortie de stock enregistrée : {{ $commande->quantite }} unité{{ $commande->quantite === 1 ? '' : 's' }} de « {{ $commande->produit->nom }} ».</p>
            <div class="document-total">Total : {{ number_format((float) $commande->total, 2, ',', ' ') }}</div>
        </div>
    </section>
@endsection
