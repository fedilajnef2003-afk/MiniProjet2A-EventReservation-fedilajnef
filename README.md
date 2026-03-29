# MiniProjet2A-EventReservation-fedilajnef


##  Description du Projet

EventReserva permet aux organisateurs de gérer des événements et aux participants de réserver leurs places facilement. L'application se distingue par :
- **Authentification Unifiée** : Un portail unique (Sliding UI) pour la connexion et l'inscription des clients et administrateurs.
- **Gestion Multi-Entités** : Utilisation de tables distinctes (`User` et `Admin`) avec une synchronisation via un `ChainProvider` de Symfony.
- **Interface Moderne** : Utilisation intensive de Stimulus, Turbo et AssetMapper pour une expérience utilisateur fluide sans rechargement de page.
- **Sécurité Avancée** : Intégration de JWT pour les échanges sécurisés et préparation pour Passkeys/WebAuthn.

## 🛠 Technologies Utilisées

- **Backend** : Symfony 6.4 (PHP 8.1+)
- **Base de Données** : MySQL 8.0
- **Frontend** : Twig, Vanilla CSS, Stimulus JS, Turbo
- **Gestion des Assets** : Symfony AssetMapper (SASS/JS sans Node)
- **Sécurité** : LexikJWTAuthenticationBundle, GesdinetJWTRefreshTokenBundle
- **Conteneurisation** : Docker & Docker Compose

##  Installation

Suivez ces étapes pour installer le projet localement :

### 1. Prérequis
- Docker et Docker Compose installés.
- PHP 8.1+ et Composer (pour les commandes console locales si besoin).

### 2. Cloner le projet
```bash
git clone <url-du-depot>
cd event-reservation
```

### 3. Lancer l'environnement Docker
Le projet utilise Docker pour la base de données (MySQL 8).
```bash
docker-compose up -d
```

### 4. Installer les dépendances
```bash
composer install
```

### 5. Configurer les variables d'environnement
Copiez le fichier `.env` et modifiez les paramètres de connexion à la base de données si nécessaire :
```bash
cp .env .env.local
```
*(Note: Par défaut, le port MySQL est configuré sur 3306 dans `compose.yaml`)*.

### 6. Créer la base de données et les migrations
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 7. Lancer le serveur local Symfony
```bash
symfony serve
# ou
php -S localhost:8000 -t public
```

### 8. Lancer les assets
L'AssetMapper compile les fichiers automatiquement. Si nécessaire, nettoyez le cache :
```bash
php bin/console asset-map:compile
```
