<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CafThé</title>
</head>
<body>
    {{-- Ath::user() renvoie l'employé actuellement connecté (l'instance du modèle Employe) --}}
    <h1>Bienvenue, {{ Auth::user()->prenom_employe }} {{ Auth::user()->nom_employe }}</h1>
    <p>Rôle : {{ Auth::user()->role }}</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Se déconnecter</button>
    </form>
</body>
</html>