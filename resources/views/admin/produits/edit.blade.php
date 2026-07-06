<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un produit - Dashboard CafThé</title>
</head>
<body>
    <h1>Modifier "{{ $produit->nom_produit }}"</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.produits.update', $produit) }}">
        @csrf
        @method('PUT') {{-- Laravel simule les méthodes PUT/PATCH/DELETE via ce champ caché --}}

        <label>Nom :</label>
        <input type="text" name="nom_produit" value="{{ old('nom_produit', $produit->nom_produit) }}" required><br>

        <label>Description :</label>
        <textarea name="description">{{ old('description', $produit->description) }}</textarea><br>

        <label>Catégorie :</label>
        <select name="categorie" required>
            <option value="The" {{ old('categorie', $produit->categorie) == 'The' ? 'selected' : '' }}>Thé</option>
            <option value="Cafe" {{ old('categorie', $produit->categorie) == 'Cafe' ? 'selected' : '' }}>Café</option>
            <option value="Accessoire" {{ old('categorie', $produit->categorie) == 'Accessoire' ? 'selected' : '' }}>Accessoire</option>
        </select><br>

        <label>Type de vente :</label>
        <select name="type_vente" required>
            <option value="Poids" {{ old('type_vente', $produit->type_vente) == 'Poids' ? 'selected' : '' }}>Au poids (grammes)</option>
            <option value="Unite" {{ old('type_vente', $produit->type_vente) == 'Unite' ? 'selected' : '' }}>En boîte (unité)</option>
        </select><br>

        <label>Taux TVA (%) :</label>
        <input type="number" step="0.01" name="tva" value="{{ old('tva', $produit->tva) }}" required><br>

        <label>Prix HT (€) :</label>
        <input type="number" step="0.01" name="prix_ht" value="{{ old('prix_ht', $produit->prix_ht) }}" required><br>

        <label>Stock :</label>
        <input type="number" name="stock" value="{{ old('stock', $produit->stock) }}" required><br>

        <label>Image (URL) :</label>
        <input type="text" name="image" value="{{ old('image', $produit->image) }}"><br>

        <label>Origine :</label>
        <input type="text" name="origine" value="{{ old('origine', $produit->origine) }}"><br>

        <button type="submit">Modifier le produit</button>
    </form>
</body>
</html>