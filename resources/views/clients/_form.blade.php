<form class="card" action="{{ $action }}" method="post" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="form-grid">
            <div class="field">
                <label for="name">Nom complet</label>
                <input id="name" name="name" type="text" value="{{ old('name', $client->name) }}" maxlength="255" autocomplete="name" required>
                @error('name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="phone">Téléphone</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $client->phone) }}" maxlength="255" autocomplete="tel" required>
                @error('phone')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="photo">Photo <span class="field-hint">(facultative)</span></label>
                <input id="photo" name="photo" type="file" accept="image/*">
                <span class="field-hint">Image JPG, PNG ou autre format image — 2 Mo maximum.</span>
                @if ($client->photo)
                    <span class="field-hint">Une nouvelle photo remplacera la photo actuelle.</span>
                @endif
                @error('photo')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
                <a class="button button-secondary" href="{{ $client->exists ? route('clients.show', $client) : route('clients.index') }}">Annuler</a>
            </div>
        </div>
    </div>
</form>
