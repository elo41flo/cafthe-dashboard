# Dashboard Vendeur — CafThé

Dashboard de gestion interne (back-office) développé dans le cadre du titre professionnel **Développeur Web et Web Mobile (DWWM)**.

Cette application permet au personnel de la boutique CafThé (thés et cafés haut de gamme) de gérer son activité commerciale au quotidien : catalogue produits, ventes en magasin, suivi des commandes en ligne, gestion des clients et pilotage via un tableau de bord.

## Contexte du projet

Ce dashboard fait partie d'un écosystème à trois briques :

- un **site e-commerce** (React) — front-office client
- une **API REST** (Node.js / Express) — couche de services
- ce **dashboard vendeur** (Laravel) — back-office

Le dashboard et le site e-commerce partagent la même base de données MySQL.

## Fonctionnalités

- **Authentification** avec gestion de deux rôles (administrateur / vendeur)
- **Gestion des produits** : CRUD complet avec filtres (catégorie, prix) et calcul automatique du prix TTC
- **Vente en magasin** : panier dynamique, identification ou création rapide de client, mise à jour automatique du stock
- **Suivi des commandes en ligne** : consultation, filtres et gestion des statuts
- **Gestion des clients** : fiches détaillées, historique et statistiques (panier moyen, produits favoris)
- **Tableau de bord** : indicateurs clés (chiffre d'affaires, ventes, abonnés) et graphiques (Chart.js)
- **Gestion du profil** : modification des informations personnelles et du mot de passe
- **Création de comptes vendeurs** (réservé aux administrateurs)

## Technologies utilisées

- **Laravel** [version à compléter] — framework PHP
- **PHP** 8.3
- **MySQL / MariaDB** — base de données
- **Blade** — moteur de templates
- **Chart.js** — graphiques du tableau de bord
- **XAMPP** — environnement de développement local

## Installation

### Prérequis

- PHP 8.2+
- Composer
- MySQL / MariaDB
- Un serveur local (XAMPP, Laragon, etc.)

### Étapes

```bash
# Cloner le dépôt
git clone [https://github.com/elo41flo/cafthe-dashboard.git]
cd cafthe-dashboard

# Installer les dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

Configurer ensuite la connexion à la base de données dans le fichier `.env` :
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafthe3
DB_USERNAME=[ton-user]
DB_PASSWORD=[ton-mot-de-passe]

Lancer le serveur de développement :

```bash
php artisan serve
```

L'application est accessible à l'adresse `http://127.0.0.1:8000`.

## Utilisation

Pour se connecter, utiliser un compte employé enregistré en base. La création de nouveaux comptes vendeurs se fait depuis le dashboard par un administrateur.

## Auteur

**[Eloise Robert]**  
Projet réalisé dans le cadre de la formation DWWM — [F@brique Numérique / 2026]

## Licence

Projet pédagogique.
