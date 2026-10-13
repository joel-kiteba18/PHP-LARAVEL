<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion · Client Management</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f4f6fb;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
        }
        * { box-sizing: border-box; }
        body { display: grid; min-width: 320px; min-height: 100vh; margin: 0; padding: 24px; place-items: center; }
        .login-shell { display: grid; width: min(100%, 940px); min-height: 560px; grid-template-columns: 1fr 1fr; overflow: hidden; border: 1px solid #e8ebf2; border-radius: 22px; background: #fff; box-shadow: 0 24px 70px rgb(27 42 79 / 10%); }
        .brand-panel { display: flex; flex-direction: column; justify-content: space-between; padding: 42px; background: linear-gradient(145deg, #314eb7, #5475ed); color: white; }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 16px; font-weight: 750; }
        .brand-mark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 11px; background: rgb(255 255 255 / 17%); font-size: 18px; }
        .brand-message { max-width: 340px; }
        .brand-message p { margin: 0 0 14px; color: #d7e0ff; font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .brand-message h1 { margin: 0; font-size: clamp(28px, 4vw, 42px); line-height: 1.1; letter-spacing: -.05em; }
        .brand-message span { display: block; margin-top: 17px; color: #e1e7ff; font-size: 14px; line-height: 1.7; }
        .brand-footer { color: #d7e0ff; font-size: 12px; }
        .form-panel { display: flex; align-items: center; padding: 48px; }
        .form-content { width: 100%; max-width: 370px; margin: 0 auto; }
        .eyebrow { margin: 0 0 9px; color: #667ee0; font-size: 11px; font-weight: 750; letter-spacing: .13em; text-transform: uppercase; }
        h2 { margin: 0; font-size: 29px; letter-spacing: -.04em; }
        .subtitle { margin: 9px 0 30px; color: #8992a5; font-size: 14px; line-height: 1.6; }
        .field { display: grid; gap: 8px; margin-bottom: 19px; }
        .field label { color: #354057; font-size: 13px; font-weight: 700; }
        .field input { width: 100%; min-height: 46px; padding: 11px 13px; border: 1px solid #dfe4ed; border-radius: 9px; outline: none; background: #fff; color: #27334b; font: inherit; font-size: 14px; }
        .field input:focus { border-color: #7891ee; box-shadow: 0 0 0 3px rgb(69 104 223 / 10%); }
        .field input[aria-invalid="true"] { border-color: #d87880; }
        .field-error { color: #bd4651; font-size: 12px; }
        .remember { display: flex; align-items: center; gap: 9px; margin: 2px 0 22px; color: #687287; font-size: 13px; }
        .remember input { width: 16px; height: 16px; accent-color: #4568df; }
        .button { display: inline-flex; width: 100%; min-height: 46px; align-items: center; justify-content: center; border: 1px solid transparent; border-radius: 9px; background: #4568df; color: #fff; cursor: pointer; font: inherit; font-size: 13px; font-weight: 750; transition: background .15s ease; }
        .button:hover { background: #3658ce; }
        .help { margin: 22px 0 0; color: #99a1b1; font-size: 12px; line-height: 1.6; text-align: center; }
        @media (max-width: 700px) {
            body { padding: 14px; }
            .login-shell { min-height: auto; grid-template-columns: 1fr; }
            .brand-panel { min-height: 220px; padding: 26px; }
            .brand-message { margin: 32px 0 24px; }
            .brand-message h1 { font-size: 29px; }
            .brand-message span, .brand-footer { display: none; }
            .form-panel { padding: 32px 25px 36px; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="brand-panel" aria-label="Présentation de l’application">
            <a class="brand" href="{{ route('login') }}">
                <span class="brand-mark" aria-hidden="true">C</span>
                <span>Client Management</span>
            </a>
            <div class="brand-message">
                <p>Votre espace de gestion</p>
                <h1>Bienvenue dans votre application.</h1>
                <span>Retrouvez vos clients, vos produits et le suivi de vos commandes au même endroit.</span>
            </div>
            <span class="brand-footer">Un espace réservé aux utilisateurs autorisés.</span>
        </section>

        <section class="form-panel" aria-labelledby="login-title">
            <div class="form-content">
                <p class="eyebrow">Connexion sécurisée</p>
                <h2 id="login-title">Se connecter</h2>
                <p class="subtitle">Saisissez votre adresse e-mail et votre mot de passe pour continuer.</p>

                <form action="{{ route('login.store') }}" method="post">
                    @csrf
                    <div class="field">
                        <label for="email">Adresse e-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            maxlength="255"
                            required
                            autofocus
                            @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                        >
                        @error('email')
                            <span class="field-error" id="email-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                        >
                        @error('password')
                            <span class="field-error" id="password-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="remember" for="remember">
                        <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                        <span>Se souvenir de moi</span>
                    </label>

                    <button class="button" type="submit">Se connecter</button>
                </form>

                <p class="help">Si vous n’avez pas encore de compte, demandez à l’administrateur de vous en créer un.</p>
            </div>
        </section>
    </main>
</body>
</html>
