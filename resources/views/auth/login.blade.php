<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Dashboard CafThé</title>
</head>
<body>
    <h1>Connexion Dashboard Vendeur</h1>

    @if ($errors->any())
        <div style="color: red";>
            @foreach ($errors->all() as $erreur )
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf {{ -- Token de sécurité anti CSRF obligatoire sur chaque formualire POST Laravel --}}

        <label>Email :</label>
        <input type="email" name="email_employe" value="{{ old('email_employe') }}" required>

        <label>Mot de passe :</label>
        <input type="password" name="mpd_employe" required>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>