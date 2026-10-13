@extends('layouts.app')

@section('title', 'Modifier ' . $client->name)
@section('breadcrumb', 'MODIFICATION')
@section('page-title', 'Modifier le client')
@section('page-description', 'Mettez à jour les coordonnées de ' . $client->name . '.')

@section('content')
    @include('clients._form', [
        'action' => route('clients.update', $client),
        'method' => 'PUT',
        'submitLabel' => 'Enregistrer les modifications',
    ])
@endsection
