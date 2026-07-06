<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes en ligne - Dashboard CafThé</title>
</head>
<body>
    <h1>Commandes en ligne</h1>

    @if (session('succes'))
        <p style="color: green;">{{ session('succes') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>N°</th>
                <th>Date</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commandes as $commande)
                <tr>
                    <td>{{ $commande->numero_commande }}</td>
                    <td>{{ $commande->date_commande }}</td>
                    <td>
                        {{-- Sécurité : le client peut être null si la relation ne remonte rien --}}
                        {{ $commande->client->prenom_client ?? '' }} {{ $commande->client->nom_client ?? '—' }}
                    </td>
                    <td>{{ $commande->montant_paiement }} €</td>
                    <td>{{ $commande->statut_de_commande }}</td>
                    <td>
                        <a href="{{ route('admin.commandes.show', $commande) }}">Voir</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>