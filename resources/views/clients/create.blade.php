@extends('layouts.app')

@section('title', 'Ajouter un client')
@section('breadcrumb', 'NOUVEAU CLIENT')
@section('page-title', 'Ajouter un client')
@section('page-description', 'Renseignez les coordonnées du nouveau client.')

@section('content')
    @include('clients._form', [
        'action' => route('clients.store'),
        'method' => 'POST',
        'submitLabel' => 'Créer le client',
    ])
@endsection
