@extends('layouts.app')

@section('titre', 'Produits')

@section('contenu')
    <h1>Gestion des produits</h1>

    <a href="{{ route('admin.produits.create') }}">+ Ajouter un produit</a>

    @if (session('succes'))
        <p style="color: green;">{{ session('succes') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Type vente</th>
                <th>Prix HT</th>
                <th>Prix TTC</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr>
                    <td>{{ $produit->nom_produit }}</td>
                    <td>{{ $produit->categorie }}</td>
                    <td>{{ $produit->type_vente }}</td>
                    <td>{{ $produit->prix_ht }} €</td>
                    <td>{{ $produit->prix_ttc }} €</td>
                    <td>{{ $produit->stock }}</td>
                    <td>
                        <a href="{{ route('admin.produits.edit', $produit) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.produits.destroy', $produit) }}"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer ce produit ?');">
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