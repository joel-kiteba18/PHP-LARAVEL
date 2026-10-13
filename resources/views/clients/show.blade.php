@extends('layouts.app')

@section('title', $client->name)
@section('breadcrumb', 'FICHE CLIENT')
@section('page-title', $client->name)
@section('page-description', 'Coordonnées et informations du client.')
@section('page-actions')
    <a class="button button-secondary" href="{{ route('clients.index') }}">← Retour aux clients</a>
    <a class="button button-primary" href="{{ route('clients.edit', $client) }}">Modifier</a>
@endsection

@section('content')
    <section class="card">
        <div class="card-header">
            <h2 class="card-title">Informations du client</h2>
        </div>
        <div class="card-body">
            <div class="detail-list">
                @if ($client->photo)
                    <div>
                        <span class="detail-label">Photo</span>
                        <img class="detail-photo" src="{{ Storage::disk('public')->url($client->photo) }}" alt="Photo de {{ $client->name }}">
                    </div>
                @endif
                <div>
                    <span class="detail-label">Nom complet</span>
                    <span class="detail-value">{{ $client->name }}</span>
                </div>
                <div>
                    <span class="detail-label">Téléphone</span>
                    <a class="detail-value" href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                </div>
                <div>
                    <span class="detail-label">Client depuis</span>
                    <span class="detail-value">{{ $client->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </section>
@endsection
