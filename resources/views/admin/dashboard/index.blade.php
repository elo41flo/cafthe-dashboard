@extends('layouts.app')

@section('titre', 'Tableau de bord')

{{-- Chart.js va dans le <head> via la section prévue à cet effet dans le layout --}}
@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .kpi-grid { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
        .kpi-card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; min-width: 160px; }
        .kpi-card strong { display: block; font-size: 1.5em; color: #7A287D; }
        .charts { display: flex; gap: 40px; flex-wrap: wrap; }
        .chart-box { width: 450px; }
    </style>
@endsection

@section('contenu')
    <h1>Tableau de bord</h1>

    {{-- ============ CARTES KPI ============ --}}
    <div class="kpi-grid">
        <div class="kpi-card">CA aujourd'hui <strong>{{ number_format($caJour, 2, ',', ' ') }} €</strong></div>
        <div class="kpi-card">CA cette semaine <strong>{{ number_format($caSemaine, 2, ',', ' ') }} €</strong></div>
        <div class="kpi-card">CA ce mois <strong>{{ number_format($caMois, 2, ',', ' ') }} €</strong></div>
        <div class="kpi-card">CA cette année <strong>{{ number_format($caAnnee, 2, ',', ' ') }} €</strong></div>
        <div class="kpi-card">Nombre de ventes <strong>{{ $nbVentes }}</strong></div>
        <div class="kpi-card">Panier moyen <strong>{{ number_format($panierMoyen, 2, ',', ' ') }} €</strong></div>
        <div class="kpi-card">Abonnés actifs <strong>{{ $nbAbonnes }}</strong></div>
    </div>

    {{-- ============ GESTION DES STOCKS ============ --}}
    <h2>Etats des stocks</h2>
    <div class="stock-section">
        {{-- Produits en rupture --}}
        <div class="stock-box">
            <h3>Produits en rupture ({{ $produitsRupture->Count() }})</h3>
            @if (produitsRupture->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Réf</th>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (produitsRupture as $produit)
                            <tr>
                                <td>#{{ $produit->numero_produit }}</td>
                                <td>{{ $produit->nom_produit }}</td>
                                <td>{{ $produit->categorie }}</td>
                                <td><span class="badge badge-danger">0</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun produit en rupture de stock</p>
            @endif
        </div>

        {{-- Produits en stock faible --}}
        <div class="stock-box">
            <h3>Stock faible ({{ $produitsStockFaible->count() }})</h3>
            @if ($produitsStockFaible->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Réf</th>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Stock restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produitsStockFaible as $produit)
                            <tr>
                                <td>#{{ $produit->numero_produit }}</td>
                                <td>{{ $produit->nom_produit }}</td>
                                <td>{{ $produit->categorie }}</td>
                                <td><span class="badge badge-warning">{{ $produit->stock }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun produit en stock faible.</p>
            @endif
        </div>
    </div>

    {{-- ============ RÉPARTITION DES ABONNEMENTS ============ --}}
    <h2>Répartition des abonnements</h2>
    <div class="carte">
        @if ($abonnesParType->count() > 0)
            <table>
                <thead>
                    <tr><th>Type d'abonnement</th><th>Nombre d'abonnés</th></tr>
                </thead>
                <tbody>
                    @foreach ($abonnesParType as $abo)
                        <tr>
                            <td>{{ $abo->type_abonnement }}</td>
                            <td>{{ $abo->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun abonné pour le moment.</p>
        @endif
    </div>

    {{-- ============ GRAPHIQUES ============ --}}
    <div class="charts">
        <div class="chart-box">
            <h3>Top 10 des produits vendus</h3>
            <canvas id="chartTopProduits"></canvas>
        </div>
        <div class="chart-box">
            <h3>Répartition par catégorie</h3>
            <canvas id="chartCategories"></canvas>
        </div>
        <div class="chart-box">
            <h3>Évolution clients (par mois)</h3>
            <canvas id="chartClients"></canvas>
        </div>
    </div>
@endsection

{{-- Le JS des graphiques va en bas de page via la section "scripts" du layout --}}
@section('scripts')
    <script>
        // --- Graphique 1 : Top produits (barres horizontales) ---
        new Chart(document.getElementById('chartTopProduits'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($topProduits->pluck('nom_produit')) !!},
                datasets: [{
                    label: 'Quantité vendue',
                    data: {!! json_encode($topProduits->pluck('total_vendu')) !!},
                    backgroundColor: '#A23EA4',
                }]
            },
            options: { indexAxis: 'y' }
        });

        // --- Graphique 2 : Répartition par catégorie (camembert) ---
        new Chart(document.getElementById('chartCategories'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($ventesParCategorie->pluck('categorie')) !!},
                datasets: [{
                    data: {!! json_encode($ventesParCategorie->pluck('total')) !!},
                    backgroundColor: ['#A23EA4', '#7A287D', '#D68FD6', '#EFB8EF'],
                }]
            }
        });

        // --- Graphique 3 : Évolution clients par mois (courbe) ---
        new Chart(document.getElementById('chartClients'), {
            type: 'line',
            data: {
                labels: {!! json_encode($clientsParMois->pluck('mois')) !!},
                datasets: [{
                    label: 'Clients actifs',
                    data: {!! json_encode($clientsParMois->pluck('nb_clients')) !!},
                    borderColor: '#A23EA4',
                    fill: false,
                }]
            }
        });
    </script>
@endsection