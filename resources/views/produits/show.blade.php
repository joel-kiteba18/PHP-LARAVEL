@extends('layouts.app')

@section('title', $produit->nom)
@section('breadcrumb', 'FICHE PRODUIT')
@section('page-title', $produit->nom)
@section('page-description', 'Informations et quantité disponible.')
@section('page-actions')
    <a class="button button-secondary" href="{{ route('produits.index') }}">← Retour aux produits</a>
    <a class="button button-primary" href="{{ route('produits.edit', $produit) }}">Modifier</a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header"><h2 class="card-title">Détails du produit</h2></div>
        <div class="card-body">
            <div class="detail-list">
                <div>
                    <span class="detail-label">Nom</span>
                    <span class="detail-value">{{ $produit->nom }}</span>
                </div>
                <div>
                    <span class="detail-label">Prix unitaire</span>
                    <span class="detail-value">{{ number_format((float) $produit->prix, 2, ',', ' ') }}</span>
                </div>
                <div>
                    <span class="detail-label">Stock disponible</span>
                    <span class="detail-value">{{ $produit->stock }} unité{{ $produit->stock === 1 ? '' : 's' }}</span>
                </div>
                @if ($produit->description)
                    <div>
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $produit->description }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
