# Plateforme de Souscription d'Assurance Automobile

Plateforme complète de souscription en ligne d'assurance automobile avec frontend Angular, backend Laravel, et API de compagnie simulée.

## 🏗️ Architecture

- **Frontend** : Angular 17 (interface web)
- **Backend** : Laravel 10 (API REST)
- **Company API** : Service séparé Node.js/Express
- **Base de données** : PostgreSQL
- **Déploiement** : Docker Compose
- **Authentification** : JWT (Laravel Sanctum)
- **Paiements** : Stripe Sandbox
- **Emails** : Mailtrap (développement)

## 🚀 Installation Rapide

### Prérequis
- Docker et Docker Compose
- Git

### 1. Cloner le projet
```bash
git clone <votre-repo>
cd plateforme-assurance-auto
```

### 2. Lancer l'environnement
```bash
docker-compose up --build
```

### 3. Accéder aux services
- **Frontend** : http://localhost:4200
- **Backend API** : http://localhost:8000
- **Company API** : http://localhost:3000
- **Base de données** : localhost:5432
- **Mailtrap** : http://localhost:8025

## 📋 Configuration

### Variables d'environnement
Copier les fichiers `.env.example` et configurer :
```bash
# Backend Laravel
cp backend/.env.example backend/.env

# Company API
cp company-api/.env.example company-api/.env
```

### Import de la grille tarifaire
```bash
# Copier votre fichier Excel dans le dossier data/
cp /chemin/vers/votre/grille_tarifaire.xlsx data/

# Importer les tarifs
docker-compose exec backend php artisan tarifs:import /data/grille_tarifaire.xlsx
```

## 🌱 Seeds et Migrations

### Base de données
```bash
# Migrations + seeds de base
docker-compose exec backend php artisan migrate:fresh --seed

# Seeds spécifiques
docker-compose exec backend php artisan seed:dev          # Utilisateurs de test
docker-compose exec backend php artisan seed:tarifs       # Tarifs (si pas d'import Excel)
```

## 🧪 Tests

### Backend
```bash
docker-compose exec backend php artisan test
```

### Frontend
```bash
docker-compose exec frontend npm run test
```

### Tests E2E
```bash
docker-compose exec frontend npm run test:e2e
```

## 📱 Comptes de Test

### Client
- Email: client@test.com
- Mot de passe: password

### Gestionnaire
- Email: gestionnaire@test.com
- Mot de passe: password

### Admin
- Email: admin@test.com
- Mot de passe: password

## 🔄 Parcours de Test

### 1. Devis (sans connexion)
1. Aller sur http://localhost:4200
2. Cliquer sur "Devis"
3. Remplir le formulaire
4. Vérifier l'estimation reçue

### 2. Souscription (avec connexion)
1. Se connecter avec un compte client
2. Cliquer sur "Souscrire"
3. Remplir le formulaire détaillé
4. Procéder au paiement (Stripe sandbox)
5. Télécharger l'attestation

### 3. Vérification QR Code
```bash
# Endpoint de vérification
GET http://localhost:3000/verify-qr?data=<qr_code_data>
```

## 📊 Endpoints Principaux

### Authentification
- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion
- `POST /api/auth/logout` - Déconnexion

### Devis et Souscription
- `POST /api/devis` - Demande de devis
- `POST /api/subscriptions` - Créer souscription

### Contrats
- `GET /api/contracts` - Liste des contrats
- `GET /api/contracts/{id}` - Détails contrat
- `POST /api/contracts/{id}/renew` - Renouvellement

### Sinistres
- `POST /api/sinistres` - Déclarer sinistre
- `GET /api/sinistres` - Liste sinistres (gestionnaire)

## 🐳 Services Docker

- **frontend** : Angular dev server
- **backend** : Laravel + Nginx
- **company-api** : API compagnie simulée
- **db** : PostgreSQL
- **redis** : Cache et sessions
- **mailhog** : Serveur mail de développement

## 🔧 Développement

### Logs
```bash
# Suivre les logs
docker-compose logs -f [service]

# Logs spécifiques
docker-compose logs -f backend
docker-compose logs -f company-api
```

### Shell
```bash
# Accéder au backend
docker-compose exec backend bash

# Accéder à la company-api
docker-compose exec company-api sh

# Accéder à la base de données
docker-compose exec db psql -U postgres -d assurance_auto
```

## 📝 Documentation API

- **Postman Collection** : `docs/postman/assurance-auto.postman_collection.json`
- **OpenAPI/Swagger** : http://localhost:8000/api/documentation

## 🚨 Dépannage

### Problèmes courants
1. **Ports déjà utilisés** : Vérifier qu'aucun service n'utilise les ports 4200, 8000, 3000, 5432
2. **Permissions Docker** : S'assurer que l'utilisateur a les droits Docker
3. **Espace disque** : Vérifier l'espace disponible pour les images Docker

### Reset complet
```bash
docker-compose down -v
docker system prune -a
docker-compose up --build
```

## 📞 Support

Pour toute question ou problème, consulter les logs Docker et vérifier la configuration des variables d'environnement.

