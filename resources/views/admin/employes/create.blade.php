@extends('layouts.app')
@section('titre', 'Créer un vendeur')
@section('contenu')
    <h1>Créer un compte vendeur</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.employes.store') }}">
        @csrf
        <label>Nom :</label>
        <input type="text" name="nom_employe" value="{{ old('nom_employe') }}" required><br>
        <label>Prénom :</label>
        <input type="text" name="prenom_employe" value="{{ old('prenom_employe') }}"><br>
        <label>Email :</label>
        <input type="email" name="email_employe" maxlength="320" required><br>
        <label>Téléphone :</label>
        <input type="text" name="telephone_employe" pattern="[0-9]{10}" maxlength="10" title="10 chiffres" ><br>
        <label>Rôle :</label>
        <select name="role" required>
            <option value="vendeur">Vendeur</option>
            <option value="admin">Admin</option>
        </select><br>
        <label>Mot de passe :</label>
        <input type="password" name="mdp_employe" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.\-_])[A-Za-z\d@$!%*?&#.\-_]{12,}$" title="12 caractères minimum, avec majuscule, minuscule, chiffre et caractère spécial" required><br>
        <button type="submit">Créer le vendeur</button>
    </form>
@endsection