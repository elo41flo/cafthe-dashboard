@extends('layouts.app')
@section('titre', 'Employés')
@section('contenu')
    <h1>Gestion des employés</h1>

    <a href="{{ route('admin.employes.create') }}" class="btn">+ Créer un vendeur</a>

    @if (session('succes'))
        <div class="message-succes">{{ session('succes') }}</div>
    @endif
    @if (session('erreur'))
        <div class="message-erreur">{{ session('erreur') }}</div>
    @endif

    <table>
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach ($employes as $employe)
                <tr>
                    <td>{{ $employe->nom_employe }}</td>
                    <td>{{ $employe->prenom_employe }}</td>
                    <td>{{ $employe->email_employe }}</td>
                    <td>{{ ucfirst($employe->role) }}</td>
                    <td>
                        <a href="{{ route('admin.employes.edit', $employe) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.employes.destroy', $employe) }}"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer cet employé ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection