<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titre', 'Dashboard') - CafThé</title>

    <style>
        /* ============ PALETTE CAFTHÉ (tons chauds / naturels) ============ */
        :root {
            --brun-fonce: #3E2723;    /* café torréfié - texte principal, sidebar */
            --brun-moyen: #6D4C41;    /* café au lait - éléments secondaires */
            --terracotta: #C1683C;    /* accent chaud - boutons, liens actifs */
            --terracotta-clair: #E08A5E;
            --creme: #F5EFE6;         /* fond général - crème */
            --creme-fonce: #EAE0D0;   /* bordures douces */
            --vert-sauge: #7A8B6F;    /* accent naturel - succès, validations */
            --blanc: #FFFDFA;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--creme);
            color: var(--brun-fonce);
            display: flex;
            min-height: 100vh;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 250px;
            background: var(--brun-fonce);
            color: var(--creme);
            display: flex;
            flex-direction: column;
            padding: 25px 0;
            position: fixed;
            height: 100vh;
        }

        .sidebar .logo {
            font-size: 1.6em;
            font-weight: 700;
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
        }
        .sidebar .logo span { color: var(--terracotta-clair); }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .sidebar nav a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 13px 25px;
            font-size: 0.95em;
            transition: background 0.2s, border-color 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar nav a:hover {
            background: rgba(255,255,255,0.06);
            color: var(--blanc);
        }
        /* Lien de la page active (voir la logique plus bas dans le HTML) */
        .sidebar nav a.actif {
            background: rgba(193,104,60,0.15);
            border-left-color: var(--terracotta);
            color: var(--blanc);
            font-weight: 600;
        }

        /* Bloc utilisateur en bas de la sidebar */
        .sidebar .user-box {
            padding: 18px 25px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85em;
        }
        .sidebar .user-box .nom { font-weight: 600; }
        .sidebar .user-box .role {
            color: var(--terracotta-clair);
            text-transform: capitalize;
        }
        .sidebar .user-box button {
            margin-top: 10px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            color: var(--creme);
            padding: 7px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            width: 100%;
            transition: background 0.2s;
        }
        .sidebar .user-box button:hover { background: rgba(255,255,255,0.1); }

        /* ============ CONTENU PRINCIPAL ============ */
        main {
            margin-left: 250px; /* laisse la place à la sidebar fixe */
            padding: 35px 45px;
            flex-grow: 1;
        }

        h1 {
            font-size: 1.8em;
            margin-bottom: 25px;
            color: var(--brun-fonce);
        }
        h2 {
            font-size: 1.3em;
            margin: 25px 0 15px;
            color: var(--brun-moyen);
        }
        h3 { color: var(--brun-moyen); margin-bottom: 12px; }

        /* ============ TABLEAUX ============ */
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--blanc);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(62,39,35,0.06);
            margin-bottom: 20px;
        }
        th {
            background: var(--brun-moyen);
            color: var(--creme);
            text-align: left;
            padding: 14px 16px;
            font-weight: 600;
            font-size: 0.9em;
        }
        td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--creme-fonce);
            font-size: 0.92em;
        }
        tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--creme); }
        tfoot th { background: var(--brun-fonce); }

        /* ============ BOUTONS & LIENS ============ */
        .btn, button[type="submit"] {
            background: var(--terracotta);
            color: var(--blanc);
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.92em;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }
        .btn:hover, button[type="submit"]:hover { background: #A9542E; }

        /* Petits boutons dans les tableaux (Modifier / Supprimer / Retirer) */
        td button, td .btn {
            padding: 6px 12px;
            font-size: 0.85em;
        }
        td a {
            color: var(--terracotta);
            font-weight: 600;
            text-decoration: none;
        }
        td a:hover { text-decoration: underline; }

        /* Lien retour ("← Retour à la liste") */
        .lien-retour {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--brun-moyen);
            text-decoration: none;
            font-size: 0.9em;
        }
        .lien-retour:hover { color: var(--terracotta); }

        /* ============ FORMULAIRES ============ */
        form label {
            display: block;
            margin: 14px 0 5px;
            font-weight: 600;
            font-size: 0.9em;
            color: var(--brun-moyen);
        }
        form input, form select, form textarea {
            width: 100%;
            max-width: 450px;
            padding: 10px 12px;
            border: 1px solid var(--creme-fonce);
            border-radius: 8px;
            font-size: 0.95em;
            font-family: inherit;
            background: var(--blanc);
        }
        form input:focus, form select:focus, form textarea:focus {
            outline: none;
            border-color: var(--terracotta);
        }
        form textarea { min-height: 90px; resize: vertical; }
        form button[type="submit"] { margin-top: 20px; }

        /* Formulaires inline dans les tableaux (pas de largeur max) */
        td form { display: inline; }
        td form input, td form select { width: auto; }

        /* ============ CARTES & MESSAGES ============ */
        .carte {
            background: var(--blanc);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 2px 12px rgba(62,39,35,0.06);
            margin-bottom: 20px;
        }

        .message-succes {
            background: rgba(122,139,111,0.15);
            color: #4A5A40;
            padding: 12px 18px;
            border-radius: 8px;
            border-left: 4px solid var(--vert-sauge);
            margin-bottom: 20px;
        }
        .message-erreur {
            background: rgba(193,104,60,0.12);
            color: #8A3E1C;
            padding: 12px 18px;
            border-radius: 8px;
            border-left: 4px solid var(--terracotta);
            margin-bottom: 20px;
        }
        .message-erreur p { margin: 3px 0; }
    </style>

    @yield('head')
</head>
<body>
    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar">
        <div class="logo">Caf<span>Thé</span></div>

        <nav>
            {{-- request()->routeIs() détecte la page active pour la surligner --}}
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'actif' : '' }}">
                Tableau de bord
            </a>
            <a href="{{ route('admin.produits.index') }}" class="{{ request()->routeIs('admin.produits.*') ? 'actif' : '' }}">
                Produits
            </a>
            <a href="{{ route('admin.commandes.index') }}" class="{{ request()->routeIs('admin.commandes.*') ? 'actif' : '' }}">
                Commandes
            </a>
            <a href="{{ route('admin.ventes.create') }}" class="{{ request()->routeIs('admin.ventes.*') ? 'actif' : '' }}">
                Nouvelle vente
            </a>
            <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'actif' : '' }}">
                Clients
            </a>
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.employes.create') }}" class="{{ request()->routeIs('admin.employes.*') ? 'actif' : '' }}">
                    Créer un vendeur
                </a>
            @endif
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.employes.index') }}" class="{{ request()->routeIs('admin.employes.*') ? 'actif' : '' }}">
                    Employés
                </a>
            @endif
            <a href="{{ route('admin.profil.edit') }}" class="{{ request()->routeIs('admin.profil.*') ? 'actif' : '' }}">
                Mon profil
            </a>
        </nav>

        <div class="user-box">
            <div class="nom">{{ Auth::user()->prenom_employe }} {{ Auth::user()->nom_employe }}</div>
            <div class="role">{{ Auth::user()->role }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Déconnexion</button>
            </form>
        </div>
    </aside>

    {{-- ============ CONTENU ============ --}}
    <main>
        @yield('contenu')
    </main>

    @yield('scripts')
</body>
</html>