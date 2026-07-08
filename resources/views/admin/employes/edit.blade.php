@extends('layouts.app')
@section('titre', 'Modifier un employé')
@section('contenu')
    <h1>Modifier {{ $employe->prenom_employe }} {{ $employe->nom_employe }}</h1>

    @if ($errors->any())
        <div class="message-erreur">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.employes.update', $employe) }}">
        @csrf
        @method('PUT')

        <label>Nom :</label>
        <input type="text" name="nom_employe" value="{{ old('nom_employe', $employe->nom_employe) }}" required>

        <label>Prénom :</label>
        <input type="text" name="prenom_employe" value="{{ old('prenom_employe', $employe->prenom_employe) }}">

        <label>Email :</label>
        <input type="email" name="email_employe" value="{{ old('email_employe', $employe->email_employe) }}" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone_employe" value="{{ old('telephone_employe', $employe->telephone_employe) }}">

        <label>Rôle :</label>
        <select name="role" required>
            <option value="vendeur" {{ $employe->role == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
            <option value="admin" {{ $employe->role == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>

        <label>Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
        <input type="password" name="mdp_employe">

        <button type="submit">Enregistrer les modifications</button>
    </form>
@endsection