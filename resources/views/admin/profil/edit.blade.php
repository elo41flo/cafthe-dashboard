@extends('layouts.app')

@section('titre', 'Mon profil')

@section('contenu')
    <h1>Mon profil</h1>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif

    @if ($errors->any())
        <div class="message-erreur">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    {{-- ============ FORMULAIRE 1 : INFOS PERSONNELLES ============ --}}
    <div class="carte">
        <h2>Informations personnelles</h2>
        <form method="POST" action="{{ route('admin.profil.infos') }}">
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

            <button type="submit">Enregistrer les infos</button>
        </form>
    </div>

    {{-- ============ FORMULAIRE 2 : MOT DE PASSE ============ --}}
    <div class="carte">
        <h2>Changer mon mot de passe</h2>
        <form method="POST" action="{{ route('admin.profil.motdepasse') }}">
            @csrf
            @method('PUT')

            <label>Ancien mot de passe :</label>
            <input type="password" name="ancien_mdp" required>

            <label>Nouveau mot de passe :</label>
            <input type="password" name="nouveau_mdp" required>

            <label>Confirmer le nouveau mot de passe :</label>
            <input type="password" name="nouveau_mdp_confirmation" required>

            <button type="submit">Changer le mot de passe</button>
        </form>
    </div>
@endsection