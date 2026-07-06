<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche client - {{ $client->nom_client }}</title>
</head>
<body>
    <a href="{{ route('admin.clients.index') }}">← Retour à la liste</a>

    <h1>{{ $client->prenom_client }} {{ $client->nom_client }}</h1>

    <h2>Coordonnées</h2>
    <p>
        Email : {{ $client->email_client }}<br>
        Téléphone : {{ $client->telephone ?? '—' }}<br>
        Adresse livraison : {{ $client->adresse_livraison ?? '—' }},
        {{ $client->code_postal_livraison ?? '' }} {{ $client->ville_livraison ?? '' }}<br>
        Abonné : {{ $client->est_abonne ? 'Oui (' . $client->type_abonnement . ')' : 'Non' }}
    </p>

    <h2>Statistiques</h2>
    <ul>
        <li>Nombre de commandes : {{ $nbCommandes }}</li>
        <li>Chiffre d'affaires total : {{ number_format($caTotal, 2, ',', ' ') }} €</li>
        <li>Panier moyen : {{ number_format($panierMoyen, 2, ',', ' ') }} €</li>
        <li>
            Produits favoris :
            @if (count($produitsFavoris) > 0)
                <ul>
                    @foreach ($produitsFavoris as $nom => $nbFois)
                        <li>{{ $nom }} ({{ $nbFois }} commande{{ $nbFois > 1 ? 's' : '' }})</li>
                    @endforeach
                </ul>
            @else
                —
            @endif
        </li>
    </ul>

    <h2>Historique des commandes</h2>
    @if ($commandes->count() > 0)
        <table border="1" cellpadding="8">
            <thead>
                <tr><th>N°</th><th>Date</th><th>Type</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach ($commandes as $commande)
                    <tr>
                        <td>{{ $commande->numero_commande }}</td>
                        <td>{{ $commande->date_commande }}</td>
                        <td>{{ $commande->type_de_commande ?? '—' }}</td>
                        <td>{{ $commande->montant_paiement }} €</td>
                        <td>{{ $commande->statut_de_commande }}</td>
                        <td><a href="{{ route('admin.commandes.show', $commande) }}">Détail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune commande pour ce client.</p>
    @endif
</body>
</html>