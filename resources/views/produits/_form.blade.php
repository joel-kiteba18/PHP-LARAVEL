<form class="card" action="{{ $action }}" method="post">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="form-grid">
            <div class="field">
                <label for="nom">Nom du produit</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom', $produit->nom) }}" maxlength="255" required>
                @error('nom')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="description">Description <span class="field-hint">(facultative)</span></label>
                <textarea class="field-control" id="description" name="description">{{ old('description', $produit->description) }}</textarea>
                @error('description')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="prix">Prix unitaire</label>
                <input id="prix" name="prix" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('prix', $produit->prix) }}" required>
                @error('prix')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="stock">Stock initial / disponible</label>
                <input id="stock" name="stock" type="number" min="0" max="2147483647" step="1" value="{{ old('stock', $produit->stock ?? 0) }}" required>
                @error('stock')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
                <a class="button button-secondary" href="{{ $produit->exists ? route('produits.show', $produit) : route('produits.index') }}">Annuler</a>
            </div>
        </div>
    </div>
</form>
