@extends('layouts.app')

@section('title', 'Ajouter un produit')
@section('breadcrumb', 'NOUVEAU PRODUIT')
@section('page-title', 'Ajouter un produit')
@section('page-description', 'Enregistrez le prix et la quantité disponible avant de créer une commande.')

@section('content')
    @include('produits._form', [
        'action' => route('produits.store'),
        'method' => 'POST',
        'submitLabel' => 'Enregistrer le produit',
    ])
@endsection
