@extends('layouts.app')

@section('title', 'Produits')
@section('breadcrumb', 'PRODUITS')
@section('page-title', 'Produits')
@section('page-description', 'Gérez votre catalogue et les quantités en stock.')
@section('page-actions')
    <a class="button button-primary" href="{{ route('produits.create') }}">＋ Ajouter un produit</a>
@endsection

@section('content')
    <section class="card" aria-label="Liste des produits">
        <div class="card-header">
            <h2 class="card-title">Catalogue</h2>
            <span class="field-hint">{{ $produits->total() }} produit{{ $produits->total() === 1 ? '' : 's' }}</span>
        </div>

        @if ($produits->isEmpty())
            <div class="empty-state">
                <h2>Aucun produit enregistré</h2>
                <p>Ajoutez d’abord vos produits pour pouvoir les sélectionner dans une commande.</p>
                <a class="button button-primary" href="{{ route('produits.create') }}">Ajouter un produit</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Produit</th>
                            <th scope="col">Prix unitaire</th>
                            <th scope="col">Stock disponible</th>
                            <th scope="col"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produits as $produit)
                            <tr>
                                <td><a class="client-cell" href="{{ route('produits.show', $produit) }}">{{ $produit->nom }}</a></td>
                                <td>{{ number_format((float) $produit->prix, 2, ',', ' ') }}</td>
                                <td>
                                    <span class="stock-badge {{ $produit->stock === 0 ? 'is-empty' : '' }}">
                                        {{ $produit->stock }} unité{{ $produit->stock === 1 ? '' : 's' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="button button-secondary button-small" href="{{ route('produits.edit', $produit) }}">Modifier</a>
                                        @if ($produit->commandes_count === 0)
                                            <form action="{{ route('produits.destroy', $produit) }}" method="post" onsubmit="return confirm('Supprimer ce produit ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="button button-danger button-small" type="submit">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($produits->hasPages())
                <div class="pagination-wrap">{{ $produits->links('pagination.clients', ['label' => 'produits']) }}</div>
            @endif
        @endif
    </section>
@endsection
