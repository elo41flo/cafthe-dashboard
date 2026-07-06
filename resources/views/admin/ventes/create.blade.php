<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle vente magasin - Dashboard CafThé</title>
</head>
<body>
    <h1>Enregistrer une vente en magasin</h1>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $erreur)
                <p>{{ $erreur }}</p>
            @endforeach
        </div>
    @endif

    {{-- Zone d'erreur JS (ex: stock insuffisant renvoyé par le serveur) --}}
    <div id="erreur-js" style="color: red;"></div>

    <form id="form-vente" method="POST" action="{{ route('admin.ventes.store') }}">
        @csrf

        {{-- ============ ÉTAPE 1 : CLIENT ============ --}}
        <h2>1. Client</h2>

        <label>
            <input type="radio" name="type_client" value="existant" checked>
            Client existant
        </label>
        <label>
            <input type="radio" name="type_client" value="nouveau">
            Nouveau client
        </label>

        {{-- Bloc client existant --}}
        <div id="bloc-existant">
            <label>Sélectionner un client :</label>
            <select name="numero_client" id="select-client">
                <option value="">-- Choisir --</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->numero_client }}">
                        {{ $client->nom_client }} {{ $client->prenom_client }} ({{ $client->email_client }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Bloc nouveau client (masqué par défaut) --}}
        <div id="bloc-nouveau" style="display:none;">
            <label>Nom :</label>
            <input type="text" name="nouveau_nom"><br>
            <label>Prénom :</label>
            <input type="text" name="nouveau_prenom"><br>
            <label>Email :</label>
            <input type="email" name="nouveau_email"><br>
        </div>

        {{-- ============ ÉTAPE 2 : PANIER ============ --}}
        <h2>2. Panier</h2>

        <label>Ajouter un produit :</label>
        <select id="select-produit">
            <option value="">-- Choisir un produit --</option>
            @foreach ($produits as $produit)
                {{-- On stocke les infos utiles dans des attributs data- pour le JS --}}
                <option value="{{ $produit->numero_produit }}"
                        data-nom="{{ $produit->nom_produit }}"
                        data-prix="{{ $produit->prix_ttc }}"
                        data-stock="{{ $produit->stock }}"
                        data-type="{{ $produit->type_vente }}">
                    {{ $produit->nom_produit }} — {{ $produit->prix_ttc }} € (stock : {{ $produit->stock }})
                </option>
            @endforeach
        </select>
        <input type="number" id="input-quantite" placeholder="Quantité" step="0.01" min="0.01" style="width:100px;">
        <button type="button" id="btn-ajouter">Ajouter au panier</button>

        <table border="1" cellpadding="8" style="margin-top:15px;">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix TTC unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="corps-panier">
                {{-- Les lignes sont ajoutées dynamiquement par le JS --}}
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Total TTC :</th>
                    <th id="total-ttc">0.00 €</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        {{-- ============ ÉTAPE 3 : PAIEMENT ============ --}}
        <h2>3. Paiement</h2>
        <label>Mode de paiement :</label>
        <select name="mode_paiement" required>
            <option value="CB">Carte bancaire</option>
            <option value="Espèces">Espèces</option>
            <option value="Chèque">Chèque</option>
        </select>

        <br><br>
        <button type="submit">Valider la vente</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Le panier est un tableau JS d'objets {numero_produit, nom, prix, quantite}
        let panier = [];

        const selectProduit = document.getElementById('select-produit');
        const inputQuantite = document.getElementById('input-quantite');
        const corpsPanier = document.getElementById('corps-panier');
        const totalTtcEl = document.getElementById('total-ttc');
        const erreurJs = document.getElementById('erreur-js');

        // --- Bascule entre client existant / nouveau client ---
        const radios = document.querySelectorAll('input[name="type_client"]');
        const blocExistant = document.getElementById('bloc-existant');
        const blocNouveau = document.getElementById('bloc-nouveau');

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'existant') {
                    blocExistant.style.display = 'block';
                    blocNouveau.style.display = 'none';
                } else {
                    blocExistant.style.display = 'none';
                    blocNouveau.style.display = 'block';
                }
            });
        });

        // --- Ajout d'un produit au panier ---
        document.getElementById('btn-ajouter').addEventListener('click', function () {
            erreurJs.textContent = '';

            const option = selectProduit.options[selectProduit.selectedIndex];
            const numeroProduit = selectProduit.value;
            const quantite = parseFloat(inputQuantite.value);

            if (!numeroProduit) {
                erreurJs.textContent = 'Sélectionne un produit.';
                return;
            }
            if (!quantite || quantite <= 0) {
                erreurJs.textContent = 'Saisis une quantité valide.';
                return;
            }

            const stock = parseFloat(option.dataset.stock);

            // On additionne la quantité déjà dans le panier pour ce produit
            const dejaDansPanier = panier
                .filter(l => l.numero_produit === numeroProduit)
                .reduce((somme, l) => somme + l.quantite, 0);

            if (quantite + dejaDansPanier > stock) {
                erreurJs.textContent = `Stock insuffisant (disponible : ${stock}).`;
                return;
            }

            panier.push({
                numero_produit: numeroProduit,
                nom: option.dataset.nom,
                prix: parseFloat(option.dataset.prix),
                quantite: quantite,
            });

            inputQuantite.value = '';
            afficherPanier();
        });

        // --- Réaffiche le tableau du panier et recalcule le total ---
        function afficherPanier() {
            corpsPanier.innerHTML = '';
            let total = 0;

            panier.forEach((ligne, index) => {
                const sousTotal = ligne.prix * ligne.quantite;
                total += sousTotal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${ligne.nom}</td>
                    <td>${ligne.prix.toFixed(2)} €</td>
                    <td>${ligne.quantite}</td>
                    <td>${sousTotal.toFixed(2)} €</td>
                    <td><button type="button" data-index="${index}" class="btn-supprimer">Retirer</button></td>
                `;
                corpsPanier.appendChild(tr);
            });

            totalTtcEl.textContent = total.toFixed(2) + ' €';

            // Rebranche les boutons "Retirer" (recréés à chaque affichage)
            document.querySelectorAll('.btn-supprimer').forEach(btn => {
                btn.addEventListener('click', function () {
                    panier.splice(parseInt(this.dataset.index), 1);
                    afficherPanier();
                });
            });
        }

        // --- À la soumission : on transforme le panier JS en champs cachés du formulaire ---
        document.getElementById('form-vente').addEventListener('submit', function (e) {
            erreurJs.textContent = '';

            if (panier.length === 0) {
                e.preventDefault();
                erreurJs.textContent = 'Le panier est vide.';
                return;
            }

            // On supprime d'éventuels champs cachés d'une soumission précédente
            document.querySelectorAll('.ligne-cachee').forEach(el => el.remove());

            // Pour chaque ligne du panier, on crée deux inputs cachés attendus par le contrôleur :
            // lignes[i][numero_produit] et lignes[i][quantite]
            panier.forEach((ligne, i) => {
                const inputProduit = document.createElement('input');
                inputProduit.type = 'hidden';
                inputProduit.name = `lignes[${i}][numero_produit]`;
                inputProduit.value = ligne.numero_produit;
                inputProduit.classList.add('ligne-cachee');
                this.appendChild(inputProduit);

                const inputQte = document.createElement('input');
                inputQte.type = 'hidden';
                inputQte.name = `lignes[${i}][quantite]`;
                inputQte.value = ligne.quantite;
                inputQte.classList.add('ligne-cachee');
                this.appendChild(inputQte);
            });
        });
    });
    </script>
</body>
</html>