@extends('layouts.app')

@section('title', 'Clients')
@section('breadcrumb', 'CLIENTS')
@section('page-title', 'Clients')
@section('page-description', 'Consultez et gérez les coordonnées de vos clients.')
@section('page-actions')
    <a class="button button-primary" href="{{ route('clients.create') }}">＋ Ajouter un client</a>
@endsection

@section('content')
    <section class="card" aria-label="Liste des clients">
        <div class="card-header">
            <h2 class="card-title">Tous les clients</h2>
            <span class="field-hint">{{ $clients->total() }} résultat{{ $clients->total() > 1 ? 's' : '' }}</span>
        </div>

        @if ($clients->isEmpty())
            <div class="empty-state">
                <h2>Aucun client pour le moment</h2>
                <p>Ajoutez votre premier client pour commencer à gérer vos contacts.</p>
                <a class="button button-primary" href="{{ route('clients.create') }}">Ajouter un client</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Ajouté le</th>
                            <th scope="col"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>
                                    <a class="client-cell" href="{{ route('clients.show', $client) }}">
                                        <span class="avatar">
                                            @if ($client->photo)
                                                <img src="{{ Storage::disk('public')->url($client->photo) }}" alt="">
                                            @else
                                                {{ mb_strtoupper(mb_substr($client->name, 0, 1)) }}
                                            @endif
                                        </span>
                                        <span>{{ $client->name }}</span>
                                    </a>
                                </td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a class="button button-secondary button-small" href="{{ route('clients.edit', $client) }}">Modifier</a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="post" onsubmit="return confirm('Supprimer ce client ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button button-danger button-small" type="submit">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($clients->hasPages())
                <div class="pagination-wrap">{{ $clients->links('pagination.clients') }}</div>
            @endif
        @endif
    </section>
@endsection
