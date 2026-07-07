@extends('layouts.app')
@section('titre', 'Commande n°' . $commande->numero_commande)
@section('contenu')
    <a href="{{ route('admin.commandes.index') }}">← Retour à la liste</a>

    <h1>Commande n°{{ $commande->numero_commande }}</h1>

    @if (session('succes'))
        <p style="color: green;">{{ session('succes') }}</p>
    @endif

    <h2>Client</h2>
    <p>
        {{ $commande->client->prenom_client ?? '' }} {{ $commande->client->nom_client ?? '—' }}<br>
        {{ $commande->client->email_client ?? '' }}
    </p>

    <h2>Détail de la commande</h2>
    <p>
        Date : {{ $commande->date_commande }}<br>
        Montant : {{ $commande->montant_paiement }} €<br>
        Mode de paiement : {{ $commande->mode_paiement }}
    </p>

    <h2>Produits commandés</h2>
    <table border="1" cellpadding="8">
        <thead>
            <tr><th>Produit</th><th>Quantité (g)</th><th>Prix TTC unitaire</th></tr>
        </thead>
        <tbody>
            @foreach ($commande->produits as $produit)
                <tr>
                    <td>{{ $produit->nom_produit }}</td>
                    <td>{{ $produit->pivot->quantite_gramme }}</td>
                    <td>{{ $produit->prix_ttc }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($commande->livraisonRetrait)
        <h2>Livraison</h2>
        <p>
            Transporteur : {{ $commande->livraisonRetrait->choix_transporteur ?? '—' }}<br>
            Date prévue : {{ $commande->livraisonRetrait->date_livraison ?? '—' }}
        </p>
    @endif

    <h2>Changer le statut</h2>
    <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}">
        @csrf
        @method('PUT')
        <select name="statut_de_commande" required>
            @foreach (['Payée', 'En attente', 'En préparation', 'Expédiée', 'Livrée'] as $statut)
                <option value="{{ $statut }}" {{ $commande->statut_de_commande == $statut ? 'selected' : '' }}>
                    {{ $statut }}
                </option>
            @endforeach
        </select>
        <button type="submit">Mettre à jour</button>
    </form>
@endsection