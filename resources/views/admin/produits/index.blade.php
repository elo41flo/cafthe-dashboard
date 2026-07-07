@extends('layouts.app')

@section('titre', 'Produits')

@section('contenu')
    <h1>Gestion des produits</h1>
    
    {{-- ============ FILTRES ============ --}}
<div class="carte">
    <form method="GET" action="{{ route('admin.produits.index') }}">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label>Catégorie :</label>
                <select name="categorie">
                    <option value="">Toutes</option>
                    <option value="The" {{ request('categorie') == 'The' ? 'selected' : '' }}>Thé</option>
                    <option value="Cafe" {{ request('categorie') == 'Cafe' ? 'selected' : '' }}>Café</option>
                    <option value="Accessoire" {{ request('categorie') == 'Accessoire' ? 'selected' : '' }}>Accessoire</option>
                </select>
            </div>

            <div>
                <label>Prix min (€) :</label>
                <input type="number" step="0.01" name="prix_min" value="{{ request('prix_min') }}" style="width: 120px;">
            </div>

            <div>
                <label>Prix max (€) :</label>
                <input type="number" step="0.01" name="prix_max" value="{{ request('prix_max') }}" style="width: 120px;">
            </div>

            <div>
                <label>Trier par :</label>
                <select name="tri">
                    <option value="">Nom (A-Z)</option>
                    <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn">Filtrer</button>
                <a href="{{ route('admin.produits.index') }}" class="btn" style="background: var(--brun-moyen);">Réinitialiser</a>
            </div>
        </div>
    </form>
</div>
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