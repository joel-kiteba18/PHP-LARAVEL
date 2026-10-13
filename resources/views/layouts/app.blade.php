<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clients') · Client Management</title>
    <script>
        if (localStorage.getItem('client-management-theme') === 'dark') {
            document.documentElement.dataset.theme = 'dark';
        }
    </script>
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
        body { min-width: 320px; min-height: 100vh; margin: 0; }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            padding: 0 36px;
            background: #fff;
            border-bottom: 1px solid #e8ebf2;
        }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 17px; font-weight: 750; letter-spacing: -.03em; }
        .brand-mark {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 11px;
            background: #e9efff;
            color: #3e62d7;
            font-size: 17px;
            font-weight: 800;
        }
        .topbar-account { display: flex; align-items: center; gap: 16px; }
        .topbar-note { color: #8992a5; font-size: 13px; }
        .logout-form { margin: 0; }
        .app-shell { display: flex; min-height: calc(100vh - 72px); }
        .sidebar { width: 242px; padding: 32px 18px; background: #fff; border-right: 1px solid #e8ebf2; }
        .nav-label { margin: 0 12px 12px; color: #a1a8b7; font-size: 10px; font-weight: 750; letter-spacing: .12em; text-transform: uppercase; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 10px; color: #687287; font-size: 14px; font-weight: 600; }
        .nav-link:hover, .nav-link.active { background: #eff3ff; color: #365bd0; }
        .nav-icon { display: grid; width: 22px; height: 22px; place-items: center; font-size: 16px; }
        .main { width: min(100% - 48px, 1180px); margin: 0 auto; padding: 42px 0 56px; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
        .eyebrow { margin: 0 0 7px; color: #8992a5; font-size: 12px; font-weight: 650; }
        h1 { margin: 0; font-size: clamp(25px, 3vw, 32px); letter-spacing: -.045em; }
        .page-description { margin: 8px 0 0; color: #7c8599; font-size: 14px; }
        .card { overflow: hidden; border: 1px solid #e8ebf2; border-radius: 15px; background: #fff; box-shadow: 0 5px 20px rgb(27 42 79 / 3%); }
        .card-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; border-bottom: 1px solid #edf0f5; }
        .card-title { margin: 0; font-size: 15px; font-weight: 700; }
        .card-body { padding: 24px; }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; gap: 8px; padding: 0 15px; border: 1px solid transparent; border-radius: 9px; cursor: pointer; font-size: 13px; font-weight: 700; transition: background .15s ease, border-color .15s ease; }
        .button-primary { background: #4568df; color: #fff; }
        .button-primary:hover { background: #3658ce; }
        .button-secondary { border-color: #e2e6ef; background: #fff; color: #59647a; }
        .button-secondary:hover { background: #f7f8fb; }
        .button-danger { border-color: #f2dadd; background: #fff; color: #be4752; }
        .button-danger:hover { background: #fff5f5; }
        .button-small { min-height: 34px; padding: 0 11px; font-size: 12px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 13px 24px; background: #fafbfe; color: #8992a5; font-size: 10px; font-weight: 750; letter-spacing: .09em; text-transform: uppercase; }
        td { padding: 15px 24px; border-top: 1px solid #f0f2f6; color: #59647a; font-size: 13px; }
        tbody tr:hover { background: #fcfdff; }
        .client-cell { display: flex; align-items: center; gap: 12px; color: #27334b; font-weight: 650; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%); }
        .avatar { display: grid; width: 38px; height: 38px; flex: 0 0 38px; place-items: center; overflow: hidden; border-radius: 50%; background: #edf1fb; color: #5470c5; font-size: 13px; font-weight: 750; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .row-actions { display: flex; justify-content: flex-end; gap: 7px; }
        .empty-state { padding: 54px 20px; text-align: center; }
        .empty-state h2 { margin: 0; font-size: 17px; }
        .empty-state p { margin: 8px 0 20px; color: #8992a5; font-size: 13px; }
        .alert { margin-bottom: 20px; padding: 13px 16px; border: 1px solid #cfe9dc; border-radius: 10px; background: #f0fbf5; color: #27714c; font-size: 13px; }
        .form-grid { display: grid; max-width: 660px; gap: 21px; }
        .field { display: grid; gap: 8px; }
        .field label { color: #354057; font-size: 13px; font-weight: 700; }
        .field input { width: 100%; min-height: 44px; padding: 10px 12px; border: 1px solid #dfe4ed; border-radius: 9px; outline: none; background: #fff; color: #27334b; font-size: 14px; }
        .field input:focus { border-color: #7891ee; box-shadow: 0 0 0 3px rgb(69 104 223 / 10%); }
        .field-hint { color: #8992a5; font-size: 12px; }
        .field-error { color: #bd4651; font-size: 12px; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 9px; margin-top: 4px; }
        .detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .detail-label { display: block; margin-bottom: 7px; color: #8992a5; font-size: 11px; font-weight: 750; letter-spacing: .08em; text-transform: uppercase; }
        .detail-value { color: #27334b; font-size: 15px; font-weight: 600; }
        .detail-photo { width: 112px; height: 112px; border-radius: 14px; object-fit: cover; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
        .stat-card { padding: 18px 20px; border: 1px solid #e8ebf2; border-radius: 13px; background: #fff; }
        .stat-label { display: block; margin-bottom: 8px; color: #8992a5; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; }
        .stat-value { color: #27334b; font-size: 21px; font-weight: 750; }
        .stock-badge { display: inline-flex; padding: 5px 9px; border-radius: 999px; background: #eaf8f0; color: #28734c; font-size: 11px; font-weight: 700; }
        .stock-badge.is-empty { background: #fff0f0; color: #b4434d; }
        .document-card { max-width: 780px; margin: 0 auto; }
        .document-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid #edf0f5; }
        .document-number { margin: 0 0 7px; color: #8992a5; font-size: 12px; }
        .document-client { margin-top: 22px; }
        .document-lines { width: 100%; margin: 24px 0; }
        .document-total { display: flex; justify-content: flex-end; padding-top: 16px; border-top: 1px solid #edf0f5; color: #27334b; font-size: 18px; font-weight: 750; }
        .alert-error { border-color: #f2dadd; background: #fff5f5; color: #a93843; }
        select.field-control, textarea.field-control { width: 100%; min-height: 44px; padding: 10px 12px; border: 1px solid #dfe4ed; border-radius: 9px; outline: none; background: #fff; color: #27334b; font: inherit; font-size: 14px; }
        textarea.field-control { min-height: 110px; resize: vertical; }
        select.field-control:focus, textarea.field-control:focus { border-color: #7891ee; box-shadow: 0 0 0 3px rgb(69 104 223 / 10%); }
        .screen-only { display: initial; }
        @media print {
            @page { margin: 18mm; }
            :root, body { min-width: 0; background: #fff; color: #111827; }
            .topbar, .sidebar, .page-heading, .screen-only, .alert { display: none !important; }
            .app-shell { display: block; min-height: auto; }
            .main { width: 100%; max-width: none; margin: 0; padding: 0; }
            .card, .stat-card { border: 0; box-shadow: none; }
            .document-card { max-width: none; }
            .card-body { padding: 0; }
            .document-header { border-color: #d1d5db; }
            th { background: #f3f4f6 !important; color: #374151; print-color-adjust: exact; }
            td, th { padding: 10px 12px; color: #111827; }
        }
        .pagination-wrap { padding: 14px 24px; border-bottom: 1px solid #edf0f5; }
        .pagination { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .pagination-summary { margin: 0; color: #8992a5; font-size: 12px; }
        .pagination-controls { display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end; gap: 5px; }
        .page-link { display: inline-flex; min-width: 36px; min-height: 36px; align-items: center; justify-content: center; padding: 0 10px; border: 1px solid #e2e6ef; border-radius: 8px; background: #fff; color: #59647a; font-size: 12px; font-weight: 650; }
        a.page-link { transition: border-color .15s ease, background .15s ease, color .15s ease; }
        a.page-link:hover { border-color: #bdcafa; background: #f5f7ff; color: #365bd0; }
        .page-link-direction { white-space: nowrap; }
        .page-link.is-disabled { border-color: #edf0f5; background: #fafbfe; color: #b1b7c4; }

        body { background: radial-gradient(ellipse at 72% 0, #eaf0ff 0, #f4f6fb 42rem); }
        .topbar { position: sticky; z-index: 5; top: 0; box-shadow: 0 4px 18px rgb(27 42 79 / 4%); }
        .brand { letter-spacing: -.04em; }
        .brand-mark { box-shadow: inset 0 0 0 1px rgb(69 104 223 / 10%); }
        .sidebar { flex: 0 0 242px; }
        .nav-link { transition: background .15s ease, color .15s ease, transform .15s ease; }
        .nav-link:hover { transform: translateX(2px); }
        .nav-link.active { box-shadow: inset 3px 0 #4568df; }
        .main { padding-top: 48px; }
        .eyebrow { letter-spacing: .08em; text-transform: uppercase; }
        h1 { color: #172033; }
        .card, .stat-card { box-shadow: 0 12px 35px rgb(27 42 79 / 5%); transition: border-color .18s ease, box-shadow .18s ease; }
        .card:hover, .stat-card:hover { border-color: #d6def3; box-shadow: 0 16px 40px rgb(27 42 79 / 8%); }
        .card-header { min-height: 68px; }
        th { background: #f6f8fc; }
        tbody tr { transition: background .15s ease; }
        .stat-card { position: relative; overflow: hidden; }
        .stat-card::after { position: absolute; top: 0; right: 0; width: 4px; height: 100%; background: #7891ee; content: ""; }
        .button { transition: background .15s ease, border-color .15s ease, transform .15s ease, box-shadow .15s ease; }
        .button:hover { transform: translateY(-1px); }
        .button-primary { box-shadow: 0 5px 12px rgb(69 104 223 / 20%); }
        .theme-toggle { min-width: 40px; padding: 0 12px; }
        .theme-icon { font-size: 15px; line-height: 1; }
        .topbar-account { gap: 12px; }
        .topbar-note { padding-left: 14px; border-left: 1px solid #e8ebf2; }

        html[data-theme="dark"] { color-scheme: dark; color: #e6ebf5; background: #0b1120; }
        html[data-theme="dark"] body { background: radial-gradient(ellipse at 72% 0, #172442 0, #0b1120 42rem); color: #e6ebf5; }
        html[data-theme="dark"] .topbar,
        html[data-theme="dark"] .sidebar { background: #111a2d; border-color: #253149; }
        html[data-theme="dark"] .topbar { box-shadow: 0 4px 18px rgb(0 0 0 / 16%); }
        html[data-theme="dark"] .brand-mark { background: #202d4c; color: #a9bbff; }
        html[data-theme="dark"] .topbar-note { border-color: #253149; color: #a3aec2; }
        html[data-theme="dark"] .nav-label,
        html[data-theme="dark"] .eyebrow,
        html[data-theme="dark"] .page-description,
        html[data-theme="dark"] .field-hint,
        html[data-theme="dark"] .stat-label,
        html[data-theme="dark"] .detail-label,
        html[data-theme="dark"] .document-number,
        html[data-theme="dark"] .pagination-summary { color: #9ba8bd; }
        html[data-theme="dark"] h1,
        html[data-theme="dark"] .card-title,
        html[data-theme="dark"] .empty-state h2 { color: #f0f3fa; }
        html[data-theme="dark"] .nav-link { color: #aeb8cb; }
        html[data-theme="dark"] .nav-link:hover,
        html[data-theme="dark"] .nav-link.active { background: #1b2944; color: #b5c5ff; }
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .stat-card { background: #111a2d; border-color: #253149; box-shadow: 0 12px 35px rgb(0 0 0 / 14%); }
        html[data-theme="dark"] .card:hover,
        html[data-theme="dark"] .stat-card:hover { border-color: #354568; box-shadow: 0 16px 40px rgb(0 0 0 / 22%); }
        html[data-theme="dark"] .card-header,
        html[data-theme="dark"] .document-header,
        html[data-theme="dark"] .document-total,
        html[data-theme="dark"] .pagination-wrap { border-color: #253149; }
        html[data-theme="dark"] th { background: #172238; color: #9ba8bd; }
        html[data-theme="dark"] td { border-color: #202c42; color: #b8c2d4; }
        html[data-theme="dark"] tbody tr:hover { background: #172238; }
        html[data-theme="dark"] .client-cell,
        html[data-theme="dark"] .detail-value,
        html[data-theme="dark"] .document-total,
        html[data-theme="dark"] .stat-value { color: #e6ebf5; }
        html[data-theme="dark"] .avatar { background: #202d4c; color: #b5c5ff; }
        html[data-theme="dark"] .button-secondary,
        html[data-theme="dark"] .page-link { border-color: #354159; background: #172238; color: #c1cbdb; }
        html[data-theme="dark"] .button-secondary:hover,
        html[data-theme="dark"] a.page-link:hover { border-color: #53699a; background: #202e49; color: #d5deff; }
        html[data-theme="dark"] .button-danger { border-color: #58343b; background: #2a1a24; color: #ffabb2; }
        html[data-theme="dark"] .button-danger:hover { background: #3b202b; }
        html[data-theme="dark"] .page-link.is-disabled { border-color: #253149; background: #111a2d; color: #647188; }
        html[data-theme="dark"] .field label { color: #c5cede; }
        html[data-theme="dark"] .field input,
        html[data-theme="dark"] select.field-control,
        html[data-theme="dark"] textarea.field-control { border-color: #354159; background: #0d1627; color: #e6ebf5; }
        html[data-theme="dark"] .empty-state p { color: #9ba8bd; }
        html[data-theme="dark"] .alert { border-color: #24533f; background: #112b22; color: #8fdfb3; }
        html[data-theme="dark"] .alert-error { border-color: #60323a; background: #321c25; color: #ffabb2; }
        html[data-theme="dark"] .stock-badge { background: #163528; color: #8fdfb3; }
        html[data-theme="dark"] .stock-badge.is-empty { background: #3b2028; color: #ffabb2; }

        @media print {
            html[data-theme="dark"], html[data-theme="dark"] body { background: #fff !important; color: #111827 !important; }
        }
        @media (max-width: 760px) {
            .topbar { height: 62px; padding: 0 12px; }
            .brand { gap: 8px; font-size: 14px; }
            .brand-mark { width: 32px; height: 32px; }
            .topbar-account { gap: 7px; }
            .topbar-note { display: none; }
            .theme-toggle { width: 34px; min-width: 34px; padding: 0; }
            .theme-label { display: none; }
            .app-shell { display: block; min-height: calc(100vh - 62px); }
            .sidebar { width: 100%; padding: 10px 14px; border-right: 0; border-bottom: 1px solid #e8ebf2; }
            .nav-label { display: none; }
            .sidebar nav { display: flex; gap: 4px; overflow-x: auto; }
            .nav-link { flex: 0 0 auto; }
            .nav-link { width: max-content; padding: 9px 12px; }
            .main { width: min(100% - 32px, 1180px); padding: 30px 0 42px; }
            .page-heading { align-items: flex-start; flex-direction: column; gap: 16px; }
            th, td { padding-right: 15px; padding-left: 15px; }
            .detail-list { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
            .pagination { align-items: flex-start; flex-direction: column; }
            .pagination-wrap { padding: 15px; }
            .pagination-controls { justify-content: flex-start; }
            .page-link { min-width: 34px; min-height: 34px; }
            .page-link-direction { padding: 0 8px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('clients.index') }}">
            <span class="brand-mark">C</span>
            <span>Client Management</span>
        </a>
        <div class="topbar-account">
            <button class="button button-secondary button-small theme-toggle" id="theme-toggle" type="button" aria-label="Activer le mode sombre" aria-pressed="false">
                <span class="theme-icon" aria-hidden="true">☾</span>
                <span class="theme-label">Mode sombre</span>
            </button>
            <span class="topbar-note">{{ auth()->user()->name }}</span>
            <form class="logout-form" action="{{ route('logout') }}" method="post">
                @csrf
                <button class="button button-secondary button-small" type="submit">Déconnexion</button>
            </form>
        </div>
    </header>

    <div class="app-shell">
        <aside class="sidebar">
            <p class="nav-label">Gestion</p>
            <nav aria-label="Navigation principale">
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                    <span class="nav-icon" aria-hidden="true">♧</span>
                    <span>Clients</span>
                </a>
                    <a class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}" href="{{ route('produits.index') }}">
                        <span class="nav-icon" aria-hidden="true">▦</span>
                        <span>Produits</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('commandes.index', 'commandes.create', 'commandes.show') ? 'active' : '' }}" href="{{ route('commandes.index') }}">
                        <span class="nav-icon" aria-hidden="true">▤</span>
                        <span>Commandes</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('commandes.sorties') ? 'active' : '' }}" href="{{ route('commandes.sorties') }}">
                        <span class="nav-icon" aria-hidden="true">⇥</span>
                        <span>État des sorties</span>
                    </a>
            </nav>
        </aside>

        <main class="main">
            <div class="page-heading">
                <div>
                    <p class="eyebrow">CLIENT MANAGEMENT / @yield('breadcrumb', 'CLIENTS')</p>
                    <h1>@yield('page-title', 'Clients')</h1>
                    @hasSection('page-description')
                        <p class="page-description">@yield('page-description')</p>
                    @endif
                </div>
                @yield('page-actions')
            </div>

            @if (session('success'))
                <div class="alert" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error" role="alert">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = themeToggle.querySelector('.theme-icon');
        const themeLabel = themeToggle.querySelector('.theme-label');

        function updateThemeToggle() {
            const isDark = document.documentElement.dataset.theme === 'dark';
            themeToggle.setAttribute('aria-pressed', String(isDark));
            themeToggle.setAttribute('aria-label', isDark ? 'Activer le mode clair' : 'Activer le mode sombre');
            themeIcon.textContent = isDark ? '☀' : '☾';
            themeLabel.textContent = isDark ? 'Mode clair' : 'Mode sombre';
        }

        updateThemeToggle();
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.dataset.theme !== 'dark';
            document.documentElement.dataset.theme = isDark ? 'dark' : 'light';
            localStorage.setItem('client-management-theme', isDark ? 'dark' : 'light');
            updateThemeToggle();
        });
    </script>
</body>
</html>
