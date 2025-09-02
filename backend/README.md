# Backend Laravel - Plateforme d'Assurance Automobile

## 🚀 Vue d'ensemble

Ce backend Laravel 10 gère l'ensemble de la logique métier de la plateforme d'assurance automobile, incluant l'authentification, la gestion des contrats, le calcul des primes, et l'intégration avec la Company API.

## 🏗️ Architecture

### Structure des Modèles

- **User** : Gestion des utilisateurs avec rôles (client, gestionnaire, admin)
- **Contract** : Contrats d'assurance avec calcul automatique des primes
- **Devis** : Devis et calculs de prime en temps réel
- **Vehicle** : Gestion des véhicules des clients
- **Garantie** : Garanties d'assurance avec coefficients
- **TarifCategory** : Grille tarifaire pour le calcul des primes
- **ClientProfile** : Profils détaillés des clients
- **Role** : Système de rôles et permissions

### Contrôleurs API

- **AuthController** : Inscription, connexion, gestion des tokens
- **DevisController** : Calcul et gestion des devis
- **ContractController** : Gestion des contrats
- **VehicleController** : Gestion des véhicules
- **PaymentController** : Gestion des paiements
- **SinistreController** : Déclaration et gestion des sinistres

## 🛠️ Installation

### Prérequis

- PHP 8.2+
- Composer
- PostgreSQL
- Redis (optionnel)

### Installation des dépendances

```bash
composer install
```

### Configuration de l'environnement

1. Copier le fichier `.env.example` vers `.env`
2. Configurer les variables d'environnement :

```env
APP_NAME="Assurance Auto"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=assurance_auto
DB_USERNAME=postgres
DB_PASSWORD=postgres

REDIS_HOST=redis
REDIS_PORT=6379

COMPANY_API_URL=http://company-api:3000
COMPANY_API_KEY=your-company-api-key

STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
```

### Génération de la clé d'application

```bash
php artisan key:generate
```

### Migration et seeding de la base de données

```bash
# Migrations
php artisan migrate

# Seeding complet
php artisan db:seed

# Seeding spécifique
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=TarifSeeder
```

### Import des tarifs depuis Excel

```bash
# Placer le fichier Excel dans /data/grille_tarifaire.xlsx
php artisan tarifs:import /data/grille_tarifaire.xlsx
```

## 🔐 Authentification

### Système de rôles

- **Client** : Accès à ses propres données
- **Gestionnaire** : Gestion des contrats et sinistres
- **Admin** : Accès complet au système

### Endpoints d'authentification

```bash
POST /api/auth/register    # Inscription
POST /api/auth/login       # Connexion
POST /api/auth/logout      # Déconnexion
POST /api/auth/refresh     # Rafraîchir token
GET  /api/auth/me          # Informations utilisateur
```

## 📊 API Endpoints

### Devis

```bash
POST /api/devis/calculate  # Calcul de devis (public)
POST /api/devis            # Créer un devis
GET  /api/devis            # Lister les devis
GET  /api/devis/{id}       # Afficher un devis
GET  /api/devis/garanties  # Liste des garanties
GET  /api/devis/tarifs     # Grille tarifaire
```

### Contrats

```bash
GET    /api/contracts              # Lister les contrats
POST   /api/contracts              # Créer un contrat
GET    /api/contracts/{id}         # Afficher un contrat
PUT    /api/contracts/{id}         # Modifier un contrat
DELETE /api/contracts/{id}         # Supprimer un contrat
POST   /api/contracts/{id}/renew   # Renouveler un contrat
GET    /api/contracts/{id}/attestation # Télécharger attestation
```

### Véhicules

```bash
GET    /api/vehicles       # Lister les véhicules
POST   /api/vehicles       # Ajouter un véhicule
GET    /api/vehicles/{id}  # Afficher un véhicule
PUT    /api/vehicles/{id}  # Modifier un véhicule
DELETE /api/vehicles/{id}  # Supprimer un véhicule
```

### Gestionnaires

```bash
GET  /api/gestionnaires/contracts           # Tous les contrats
GET  /api/gestionnaires/sinistres           # Tous les sinistres
PUT  /api/gestionnaires/sinistres/{id}      # Valider/rejeter sinistre
POST /api/gestionnaires/contracts/{id}/cancel # Annuler contrat
POST /api/gestionnaires/notifications/send  # Envoyer notification
```

### Administrateurs

```bash
GET  /api/admin/users              # Gestion des utilisateurs
PUT  /api/admin/users/{id}/toggle-status # Activer/désactiver
GET  /api/admin/logs               # Logs d'audit
GET  /api/admin/stats              # Statistiques
```

## 🧮 Calcul des Primes

### Formule de calcul

```
Prime de base = Tarif mensuel × Durée en mois
Prime garanties = Prime de base × Σ(Coefficients garanties)
Taxes = (Prime de base + Prime garanties) × Taux de taxe
Prime totale = Prime de base + Prime garanties + Taxes
```

### Coefficients par défaut

- **Vol et Vandalisme** : 1.20
- **Incendie** : 0.80
- **Bris de Glace** : 1.00
- **Défense et Recours** : 1.10
- **Dommages Collision** : 1.50

## 🗄️ Base de Données

### Tables principales

- `users` : Utilisateurs du système
- `roles` : Rôles et permissions
- `user_roles` : Relation many-to-many users/roles
- `client_profiles` : Profils détaillés des clients
- `vehicles` : Véhicules des clients
- `garanties` : Garanties d'assurance
- `tarif_categories` : Grille tarifaire
- `contracts` : Contrats d'assurance
- `contract_garanties` : Relation many-to-many contrats/garanties
- `devis` : Devis et calculs
- `sinistres` : Déclarations de sinistres
- `payments` : Paiements
- `notifications` : Notifications système
- `audit_logs` : Logs d'audit

## 🧪 Tests

### Tests unitaires

```bash
php artisan test
```

### Tests spécifiques

```bash
# Tests des modèles
php artisan test --filter=UserTest
php artisan test --filter=ContractTest

# Tests des contrôleurs
php artisan test --filter=DevisControllerTest
php artisan test --filter=AuthControllerTest
```

## 📧 Notifications

### Types de notifications

- **Échéance de contrat** : J-30, J-7, J-1
- **Validation de sinistre**
- **Paiement reçu**
- **Renouvellement disponible**

### Configuration des notifications

```env
CONTRACT_EXPIRY_NOTIFICATION_DAYS=30,7,1
```

## 🔄 Tâches planifiées

### Scheduler Laravel

```bash
# Ajouter au crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Tâches automatiques

- Vérification des contrats expirant
- Envoi des notifications d'échéance
- Nettoyage des logs anciens
- Synchronisation avec la Company API

## 🚨 Logs et Monitoring

### Logs d'audit

- Actions utilisateurs
- Modifications de données
- Tentatives d'accès
- Erreurs système

### Endpoint de santé

```bash
GET /api/health
```

## 🔒 Sécurité

### Middleware de sécurité

- Authentification Sanctum
- Validation des rôles
- Rate limiting
- Validation des données
- Protection CSRF

### Validation des données

- Règles de validation strictes
- Sanitisation des entrées
- Validation côté serveur
- Messages d'erreur personnalisés

## 📚 Documentation API

### Collection Postman

Une collection Postman complète est disponible dans `docs/postman/` avec tous les endpoints et exemples de requêtes.

### Swagger/OpenAPI

Documentation OpenAPI disponible via l'endpoint `/api/documentation` (si configuré).

## 🐳 Docker

### Image Docker

```dockerfile
FROM php:8.2-fpm
# ... configuration complète dans Dockerfile
```

### Variables d'environnement Docker

```env
DB_HOST=db
REDIS_HOST=redis
COMPANY_API_URL=http://company-api:3000
```

## 🚀 Déploiement

### Production

1. Configurer l'environnement de production
2. Optimiser l'autoloader : `composer install --optimize-autoloader --no-dev`
3. Vider le cache : `php artisan config:cache`
4. Configurer le serveur web (Nginx/Apache)

### Variables d'environnement critiques

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-production-key
DB_PASSWORD=strong-production-password
COMPANY_API_KEY=production-api-key
STRIPE_SECRET=production-stripe-secret
```

## 📞 Support

Pour toute question ou problème :

1. Vérifier les logs dans `storage/logs/`
2. Consulter la documentation des erreurs
3. Vérifier la configuration de l'environnement
4. Tester les endpoints avec Postman

## 🔄 Mise à jour

### Mise à jour des dépendances

```bash
composer update
php artisan migrate
php artisan config:cache
```

### Mise à jour de la base de données

```bash
php artisan migrate:status
php artisan migrate
php artisan db:seed --class=TarifSeeder
```
