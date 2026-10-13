@extends('layouts.app')

@section('title', 'Modifier ' . $produit->nom)
@section('breadcrumb', 'MODIFICATION PRODUIT')
@section('page-title', 'Modifier le produit')
@section('page-description', 'Mettez à jour le prix, la description ou le stock.')

@section('content')
    @include('produits._form', [
        'action' => route('produits.update', $produit),
        'method' => 'PUT',
        'submitLabel' => 'Enregistrer les modifications',
    ])
@endsection
