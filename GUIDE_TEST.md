# Guide de Test Manuel - Plateforme Assurance Auto

Ce guide détaille tous les tests manuels à effectuer pour valider le bon fonctionnement de la plateforme.

## 🚀 Prérequis

- Docker et Docker Compose installés
- Navigateur web moderne (Chrome, Firefox, Edge)
- Postman ou équivalent pour tester l'API
- Fichier Excel de grille tarifaire (optionnel)

## 📋 Checklist de Démarrage

- [ ] `docker-compose up --build` exécuté avec succès
- [ ] Tous les services sont démarrés (frontend, backend, company-api, db, redis, mailhog)
- [ ] Base de données initialisée avec `php artisan migrate:fresh --seed`
- [ ] Tarifs importés avec `php artisan seed:tarifs` ou `php artisan tarifs:import /data/grille_tarifaire.xlsx`

## 🧪 Tests Frontend (Interface Utilisateur)

### 1. Page d'Accueil
- [ ] Page accessible sur http://localhost:4200
- [ ] Bouton "Devis" visible et cliquable
- [ ] Bouton "Souscrire" visible et cliquable
- [ ] Design responsive sur mobile et desktop

### 2. Test Devis (Sans Connexion)
- [ ] Clic sur "Devis" redirige vers le formulaire
- [ ] Formulaire contient tous les champs requis :
  - [ ] Informations véhicule (immat, marque, modèle, année, puissance fiscale)
  - [ ] Sélection garanties (menu à choix multiple)
  - [ ] Durée du contrat (1-12 mois)
  - [ ] Informations client (nom, email, téléphone)
- [ ] Validation côté client fonctionne
- [ ] Envoi du formulaire retourne une estimation
- [ ] Calcul de prime est cohérent avec la grille tarifaire

### 3. Test Inscription/Connexion
- [ ] Clic sur "Souscrire" redirige vers inscription si non connecté
- [ ] Formulaire d'inscription complet :
  - [ ] Nom, email, mot de passe, confirmation
  - [ ] Profil client (adresse, permis, expérience)
- [ ] Validation des données d'inscription
- [ ] Connexion avec les identifiants créés
- [ ] Redirection vers le dashboard après connexion

### 4. Test Souscription (Avec Connexion)
- [ ] Formulaire de souscription détaillé
- [ ] Sélection du véhicule (existant ou nouveau)
- [ ] Choix des garanties
- [ ] Affichage du devis final
- [ ] Bouton "Payer" fonctionnel
- [ ] Redirection vers Stripe sandbox
- [ ] Paiement simulé avec succès
- [ ] Création du contrat après paiement
- [ ] Envoi de l'attestation par email
- [ ] Téléchargement de l'attestation PDF

### 5. Dashboard Client
- [ ] Liste des contrats actifs
- [ ] Informations détaillées de chaque contrat
- [ ] Bouton de téléchargement d'attestation
- [ ] Bouton de renouvellement (si éligible)
- [ ] Formulaire de déclaration de sinistre
- [ ] Historique des paiements
- [ ] Notifications d'échéance

### 6. Dashboard Gestionnaire
- [ ] Accès restreint aux gestionnaires uniquement
- [ ] Liste de tous les contrats
- [ ] Liste de tous les sinistres avec filtres
- [ ] Validation/rejet des sinistres
- [ ] Annulation de contrats
- [ ] Consultation des échéanciers
- [ ] Envoi de messages aux clients

### 7. Dashboard Admin
- [ ] Accès restreint aux admins uniquement
- [ ] Gestion des utilisateurs (création, modification, blocage)
- [ ] Gestion des rôles et permissions
- [ ] Logs d'activité
- [ ] Paramètres système

## 🔌 Tests API (Backend Laravel)

### 1. Authentification
- [ ] `POST /api/auth/register` - Création d'utilisateur
- [ ] `POST /api/auth/login` - Connexion et récupération du token
- [ ] `POST /api/auth/logout` - Déconnexion
- [ ] `POST /api/auth/refresh` - Rafraîchissement du token
- [ ] Validation des tokens JWT
- [ ] Gestion des rôles et permissions

### 2. Devis
- [ ] `POST /api/devis/calculate` - Calcul sans sauvegarde
- [ ] `POST /api/devis` - Création avec sauvegarde
- [ ] `GET /api/devis` - Liste des devis
- [ ] `GET /api/devis/{id}` - Détail d'un devis
- [ ] Calcul correct selon la grille tarifaire
- [ ] Application des coefficients de garanties

### 3. Véhicules
- [ ] `POST /api/vehicles` - Ajout d'un véhicule
- [ ] `GET /api/vehicles` - Liste des véhicules
- [ ] `GET /api/vehicles/{id}` - Détail d'un véhicule
- [ ] `PUT /api/vehicles/{id}` - Modification d'un véhicule
- [ ] Validation des données (immatriculation, puissance fiscale)

### 4. Contrats
- [ ] `POST /api/subscriptions` - Création depuis un devis
- [ ] `GET /api/contracts` - Liste des contrats
- [ ] `GET /api/contracts/{id}` - Détail d'un contrat
- [ ] `POST /api/contracts/{id}/renew` - Renouvellement
- [ ] Gestion des statuts (draft, active, expired, cancelled)

### 5. Paiements
- [ ] `POST /api/payments/simulate` - Simulation de paiement
- [ ] `GET /api/payments` - Liste des paiements
- [ ] Intégration Stripe sandbox
- [ ] Webhooks de paiement
- [ ] Gestion des échecs de paiement

### 6. Sinistres
- [ ] `POST /api/sinistres` - Déclaration de sinistre
- [ ] `GET /api/sinistres` - Liste des sinistres
- [ ] `PUT /api/sinistres/{id}` - Modification du statut
- [ ] Workflow de validation par les gestionnaires

### 7. Gestionnaires
- [ ] `GET /api/gestionnaires/contracts` - Tous les contrats
- [ ] `GET /api/gestionnaires/sinistres` - Tous les sinistres
- [ ] `PUT /api/gestionnaires/sinistres/{id}` - Validation sinistre
- [ ] Gestion des échéances et notifications

### 8. Admin
- [ ] `POST /api/admin/users` - Création d'utilisateur
- [ ] `GET /api/admin/users` - Liste des utilisateurs
- [ ] `PUT /api/admin/users/{id}` - Modification utilisateur
- [ ] Gestion des rôles et permissions

## 🏢 Tests Company API

### 1. Calcul de Devis
- [ ] `POST /api/quote` - Calcul de prime
- [ ] Utilisation correcte de la grille tarifaire
- [ ] Application des coefficients de garanties
- [ ] Gestion des véhicules non éligibles
- [ ] Validation des données d'entrée

### 2. Génération d'Attestation
- [ ] `POST /api/issue` - Création de contrat
- [ ] Génération PDF avec toutes les informations
- [ ] Inclusion du QR code
- [ ] Signature numérique de l'attestation

### 3. Vérification QR Code
- [ ] `GET /api/verify-qr` - Vérification du statut
- [ ] Décodage correct des données
- [ ] Vérification de la signature
- [ ] Retour des informations du contrat

### 4. Webhooks
- [ ] `POST /api/webhook-payment` - Réception des notifications
- [ ] Traitement des événements Stripe
- [ ] Mise à jour des statuts de contrat

## 📊 Tests Base de Données

### 1. Migrations
- [ ] Toutes les tables créées correctement
- [ ] Contraintes et index appliqués
- [ ] Relations entre tables fonctionnelles

### 2. Seeds
- [ ] Utilisateurs de test créés (client, gestionnaire, admin)
- [ ] Garanties de base créées
- [ ] Tarifs d'exemple ou importés depuis Excel
- [ ] Données de test cohérentes

### 3. Intégrité des Données
- [ ] Contraintes de clés étrangères respectées
- [ ] Validation des données côté base
- [ ] Gestion des suppressions en cascade

## 🔒 Tests de Sécurité

### 1. Authentification
- [ ] Protection des routes sensibles
- [ ] Validation des tokens JWT
- [ ] Gestion des sessions expirées
- [ ] Protection CSRF

### 2. Autorisation
- [ ] Vérification des rôles et permissions
- [ ] Accès restreint aux ressources
- [ ] Séparation des données entre utilisateurs
- [ ] Protection des endpoints admin

### 3. Validation des Données
- [ ] Sanitisation des entrées
- [ ] Validation côté serveur
- [ ] Protection contre les injections SQL
- [ ] Limitation de la taille des fichiers

## 📧 Tests Email

### 1. Configuration SMTP
- [ ] Serveur Mailtrap accessible
- [ ] Envoi d'emails de test
- [ ] Réception dans l'interface Mailtrap

### 2. Templates d'Email
- [ ] Email de bienvenue après inscription
- [ ] Email de confirmation de contrat
- [ ] Email d'attestation avec PDF
- [ ] Notifications d'échéance
- [ ] Emails de sinistre

## 🧪 Tests de Performance

### 1. Temps de Réponse
- [ ] Chargement de la page d'accueil < 3s
- [ ] Calcul de devis < 2s
- [ ] Création de contrat < 5s
- [ ] Génération PDF < 10s

### 2. Charge
- [ ] Support de 10 utilisateurs simultanés
- [ ] Gestion des requêtes concurrentes
- [ ] Performance de la base de données

## 🐛 Tests de Robustesse

### 1. Gestion d'Erreurs
- [ ] Erreurs 404, 500, 403 affichées correctement
- [ ] Messages d'erreur informatifs
- [ ] Logs d'erreur enregistrés
- [ ] Récupération après erreur

### 2. Données Invalides
- [ ] Formulaire avec données manquantes
- [ ] Données de format incorrect
- [ ] Valeurs hors limites
- [ ] Caractères spéciaux et injection

### 3. Disponibilité des Services
- [ ] Fonctionnement sans base de données
- [ ] Fonctionnement sans Company API
- [ ] Fonctionnement sans Redis
- [ ] Récupération automatique des services

## 📱 Tests Multi-Plateformes

### 1. Navigateurs
- [ ] Chrome (dernière version)
- [ ] Firefox (dernière version)
- [ ] Safari (dernière version)
- [ ] Edge (dernière version)

### 2. Responsive Design
- [ ] Mobile (320px - 768px)
- [ ] Tablet (768px - 1024px)
- [ ] Desktop (1024px+)
- [ ] Orientation portrait/paysage

## 🔄 Tests de Workflow Complet

### 1. Parcours Client Standard
1. [ ] Visite de la page d'accueil
2. [ ] Demande de devis (sans connexion)
3. [ ] Inscription
4. [ ] Connexion
5. [ ] Ajout d'un véhicule
6. [ ] Demande de devis détaillé
7. [ ] Paiement et souscription
8. [ ] Téléchargement de l'attestation
9. [ ] Déclaration d'un sinistre
10. [ ] Consultation du dashboard

### 2. Parcours Gestionnaire
1. [ ] Connexion avec compte gestionnaire
2. [ ] Consultation des contrats
3. [ ] Gestion des sinistres
4. [ ] Validation d'un sinistre
5. [ ] Envoi de notifications

### 3. Parcours Admin
1. [ ] Connexion avec compte admin
2. [ ] Création d'un gestionnaire
3. [ ] Gestion des utilisateurs
4. [ ] Consultation des logs

## 📝 Rapport de Test

### Template de Rapport
```
Date: [DATE]
Testeur: [NOM]
Version: [VERSION]

Résumé:
- Tests effectués: X/Y
- Succès: X
- Échecs: X
- Bloquants: X

Détail des Tests:
[LISTE DÉTAILLÉE]

Problèmes Identifiés:
[DESCRIPTION DES PROBLÈMES]

Recommandations:
[ACTIONS À EFFECTUER]
```

## 🚨 Critères d'Acceptation

### Critères Automatisables
- [ ] Le flux Devis retourne une estimation correcte
- [ ] Le bouton Souscrire redirige vers l'inscription si non connecté
- [ ] Après paiement, le contrat est créé et l'attestation envoyée
- [ ] Le QR code renvoie le statut "assuré" avec les détails

### Critères Manuels
- [ ] Interface utilisateur intuitive et responsive
- [ ] Performance acceptable sur tous les navigateurs
- [ ] Gestion d'erreurs claire et informative
- [ ] Workflows complets fonctionnels

## 🔧 Commandes de Test

### Tests Backend
```bash
# Tests unitaires
docker-compose exec backend php artisan test

# Tests avec couverture
docker-compose exec backend php artisan test --coverage

# Tests spécifiques
docker-compose exec backend php artisan test --filter=DevisController
```

### Tests Frontend
```bash
# Tests unitaires
docker-compose exec frontend npm run test

# Tests E2E
docker-compose exec frontend npm run test:e2e

# Linting
docker-compose exec frontend npm run lint
```

### Tests Company API
```bash
# Tests unitaires
docker-compose exec company-api npm run test

# Tests avec surveillance
docker-compose exec company-api npm run test:watch
```

### Vérification des Services
```bash
# Vérifier les logs
docker-compose logs -f [service]

# Vérifier la base de données
docker-compose exec db psql -U postgres -d assurance_auto

# Vérifier les emails
# Ouvrir http://localhost:8025
```

## 📞 Support et Dépannage

### Problèmes Courants
1. **Ports déjà utilisés** : Vérifier qu'aucun service n'utilise les ports 4200, 8000, 3000, 5432
2. **Permissions Docker** : S'assurer que l'utilisateur a les droits Docker
3. **Espace disque** : Vérifier l'espace disponible pour les images Docker
4. **Variables d'environnement** : Vérifier la configuration des fichiers .env

### Logs Importants
- Backend Laravel : `docker-compose logs -f backend`
- Company API : `docker-compose logs -f company-api`
- Base de données : `docker-compose logs -f db`
- Frontend : `docker-compose logs -f frontend`

### Reset Complet
```bash
# Arrêter et nettoyer tous les services
docker-compose down -v
docker system prune -a

# Redémarrer
docker-compose up --build
```

---

**Note** : Ce guide doit être complété au fur et à mesure des tests et des découvertes de bugs ou d'améliorations.

