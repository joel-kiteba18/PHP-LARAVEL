@extends('layouts.app')

@section('title', 'Nouvelle commande')
@section('breadcrumb', 'NOUVELLE COMMANDE')
@section('page-title', 'Nouvelle commande')
@section('page-description', 'Choisissez un client, un produit et une quantité. Le stock sera décrémenté à la validation.')

@section('content')
    @if ($clients->isEmpty() || $produits->isEmpty())
        <section class="card">
            <div class="empty-state">
                <h2>Préparez les éléments de la commande</h2>
                @if ($clients->isEmpty())
                    <p>Ajoutez d’abord un client.</p>
                    <a class="button button-primary" href="{{ route('clients.create') }}">Ajouter un client</a>
                @endif
                @if ($produits->isEmpty())
                    <p>Ajoutez d’abord au moins un produit.</p>
                    <a class="button button-primary" href="{{ route('produits.create') }}">Ajouter un produit</a>
                @endif
            </div>
        </section>
    @else
        <form class="card" action="{{ route('commandes.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label for="client_id">Client</label>
                        <select class="field-control" id="client_id" name="client_id" required>
                            <option value="">Sélectionner un client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                    {{ $client->name }} — {{ $client->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="produit_id">Produit</label>
                        <select class="field-control" id="produit_id" name="produit_id" required>
                            <option value="">Sélectionner un produit</option>
                            @foreach ($produits as $produit)
                                <option
                                    value="{{ $produit->id }}"
                                    data-price="{{ $produit->prix }}"
                                    data-stock="{{ $produit->stock }}"
                                    @selected(old('produit_id') == $produit->id)
                                    @disabled($produit->stock < 1)
                                >
                                    {{ $produit->nom }} — {{ number_format((float) $produit->prix, 2, ',', ' ') }} — stock : {{ $produit->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('produit_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="quantite">Quantité</label>
                        <input id="quantite" name="quantite" type="number" min="1" step="1" value="{{ old('quantite', 1) }}" required>
                        <span class="field-hint" id="order-estimate">Choisissez un produit pour voir son prix et le total estimé.</span>
                        @error('quantite')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button class="button button-primary" type="submit">Créer la commande et sortir le stock</button>
                        <a class="button button-secondary" href="{{ route('commandes.index') }}">Annuler</a>
                    </div>
                </div>
            </div>
        </form>
        <script>
            const productSelect = document.getElementById('produit_id');
            const quantityInput = document.getElementById('quantite');
            const estimate = document.getElementById('order-estimate');

            function updateOrderEstimate() {
                const option = productSelect.selectedOptions[0];
                const quantity = Number(quantityInput.value);

                if (!option?.dataset.price || !quantity) {
                    estimate.textContent = 'Choisissez un produit pour voir son prix et le total estimé.';
                    return;
                }

                const price = Number(option.dataset.price);
                const stock = Number(option.dataset.stock);
                const total = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price * quantity);
                estimate.textContent = `Prix unitaire : ${price.toFixed(2)} · Stock disponible : ${stock} · Total estimé : ${total}`;
            }

            productSelect.addEventListener('change', updateOrderEstimate);
            quantityInput.addEventListener('input', updateOrderEstimate);
            updateOrderEstimate();
        </script>
    @endif
@endsection
