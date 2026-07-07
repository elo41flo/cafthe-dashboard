@extends('layouts.app')
@section('titre', 'Commandes en ligne')
@section('contenu')
    <h1>Commandes en ligne</h1>

    {{-- ============ FILTRES ============ --}}
<div class="carte">
    <form method="GET" action="{{ route('admin.commandes.index') }}">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label>Statut :</label>
                <select name="statut">
                    <option value="">Tous</option>
                    @foreach (['Payée', 'En attente', 'En préparation', 'Expédiée', 'Livrée'] as $statut)
                        <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                            {{ $statut }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Du :</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}">
            </div>

            <div>
                <label>Au :</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}">
            </div>

            <div>
                <button type="submit" class="btn">Filtrer</button>
                <a href="{{ route('admin.commandes.index') }}" class="btn" style="background: var(--brun-moyen);">Réinitialiser</a>
            </div>
        </div>
    </form>
</div>

    @if (session('succes'))
        <p style="color: green;">{{ session('succes') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr><th>N°</th><th>Date</th><th>Client</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach ($commandes as $commande)
                <tr>
                    <td>{{ $commande->numero_commande }}</td>
                    <td>{{ $commande->date_commande }}</td>
                    <td>{{ $commande->client->prenom_client ?? '' }} {{ $commande->client->nom_client ?? '—' }}</td>
                    <td>{{ $commande->montant_paiement }} €</td>
                    <td>{{ $commande->statut_de_commande }}</td>
                    <td><a href="{{ route('admin.commandes.show', $commande) }}">Voir</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection