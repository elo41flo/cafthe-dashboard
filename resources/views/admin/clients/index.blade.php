<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Dashboard CafThé</title>
</head>
<body>
    <h1>Gestion des clients</h1>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Nb commandes</th>
                <th>Abonné</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->nom_client }}</td>
                    <td>{{ $client->prenom_client }}</td>
                    <td>{{ $client->email_client }}</td>
                    <td>{{ $client->commandes_count }}</td>
                    <td>{{ $client->est_abonne ? 'Oui' : 'Non' }}</td>
                    <td><a href="{{ route('admin.clients.show', $client) }}">Voir la fiche</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>