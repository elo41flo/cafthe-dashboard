<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Dashboard CafThé</title>

    <style>
        :root {
            --brun-fonce: #3E2723;
            --brun-moyen: #6D4C41;
            --terracotta: #C1683C;
            --creme: #F5EFE6;
            --creme-fonce: #EAE0D0;
            --blanc: #FFFDFA;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            /* Dégradé chaud café en fond */
            background: linear-gradient(135deg, #3E2723 0%, #6D4C41 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .carte-login {
            background: var(--blanc);
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            padding: 45px 40px;
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            font-size: 2.2em;
            font-weight: 700;
            color: var(--brun-fonce);
            margin-bottom: 8px;
        }
        .logo span { color: var(--terracotta); }

        .sous-titre {
            text-align: center;
            color: var(--brun-moyen);
            font-size: 0.9em;
            margin-bottom: 35px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.88em;
            color: var(--brun-moyen);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--creme-fonce);
            border-radius: 10px;
            font-size: 0.95em;
            margin-bottom: 20px;
            background: var(--creme);
            transition: border-color 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--terracotta);
            background: var(--blanc);
        }

        button {
            width: 100%;
            background: var(--terracotta);
            color: var(--blanc);
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover { background: #A9542E; }

        .message-erreur {
            background: rgba(193,104,60,0.12);
            color: #8A3E1C;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid var(--terracotta);
            margin-bottom: 25px;
            font-size: 0.9em;
        }
        .message-erreur p { margin: 2px 0; }
    </style>
</head>
<body>
    <div class="carte-login">
        <div class="logo">Caf<span>Thé</span></div>
        <p class="sous-titre">Espace vendeur — Connexion</p>

        @if ($errors->any())
            <div class="message-erreur">
                @foreach ($errors->all() as $erreur)
                    <p>{{ $erreur }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <label>Email</label>
            <input type="email" name="email_employe" value="{{ old('email_employe') }}" required>

            <label>Mot de passe</label>
            <input type="password" name="mdp_employe" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>