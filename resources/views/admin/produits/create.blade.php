@extends('layouts.app')
@section('titre', 'Ajouter un produit')
@section('contenu')
    <h1>Ajouter un produit</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.produits.store') }}">
        @csrf
        <label>Nom :</label>
        <input type="text" name="nom_produit" value="{{ old('nom_produit') }}" required><br>
        <label>Description :</label>
        <textarea name="description">{{ old('description') }}</textarea><br>
        <label>Catégorie :</label>
        <select name="categorie" required>
            <option value="The" {{ old('categorie') == 'The' ? 'selected' : '' }}>Thé</option>
            <option value="Cafe" {{ old('categorie') == 'Cafe' ? 'selected' : '' }}>Café</option>
            <option value="Accessoire" {{ old('categorie') == 'Accessoire' ? 'selected' : '' }}>Accessoire</option>
        </select><br>
        <label>Type de vente :</label>
        <select name="type_vente" required>
            <option value="Poids" {{ old('type_vente') == 'Poids' ? 'selected' : '' }}>Au poids (grammes)</option>
            <option value="Unite" {{ old('type_vente') == 'Unite' ? 'selected' : '' }}>En boîte (unité)</option>
        </select><br>
        <label>Taux TVA (%) :</label>
        <input type="number" step="0.01" name="tva" value="{{ old('tva', 5.5) }}" required><br>
        <label>Prix HT (€) :</label>
        <input type="number" step="0.01" name="prix_ht" value="{{ old('prix_ht') }}" required><br>
        <label>Stock :</label>
        <input type="number" name="stock" value="{{ old('stock', 0) }}" required><br>
        <label>Image (URL) :</label>
        <input type="text" name="image" value="{{ old('image') }}"><br>
        <label>Origine :</label>
        <input type="text" name="origine" value="{{ old('origine') }}"><br>
        <button type="submit">Créer le produit</button>
    </form>
@endsection