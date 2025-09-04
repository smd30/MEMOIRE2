# Interface Client KDSAssur - Basée sur les Cas d'Usage UML

## 📋 Vue d'ensemble

L'interface client de KDSAssur a été entièrement refaite pour correspondre exactement au diagramme de cas d'usage UML fourni. Elle couvre tous les cas d'usage identifiés pour le client.

## 🎯 Cas d'Usage Implémentés

### 1. **creer un compte** (s'authentifier)
- **Page**: `/register` et `/login`
- **Fonctionnalités**:
  - Inscription avec tous les champs requis (nom, prénom, email, téléphone, adresse, mot de passe)
  - Connexion sécurisée
  - Gestion des tokens d'authentification
  - Validation côté client et serveur

### 2. **Demander un devis** (renseigner les infos + choisir compagnies & garanties)
- **Page**: `/devis/nouveau`
- **Processus en 3 étapes**:
  - **Étape 1**: Informations du véhicule (marque, modèle, année, immatriculation, puissance, carburant, usage)
  - **Étape 2**: Informations du conducteur (âge, date d'obtention du permis, expérience)
  - **Étape 3**: Choix des garanties et compagnies d'assurance
- **Fonctionnalités**:
  - Formulaire multi-étapes avec validation
  - Sélection multiple de garanties et compagnies
  - Barre de progression
  - Navigation intuitive

### 3. **souscrire à une assurance** (payer + télécharger l'attestation)
- **Pages**: `/souscription/:devisId` et `/attestation/:contratId`
- **Fonctionnalités**:
  - Processus de souscription basé sur un devis accepté
  - Intégration système de paiement
  - Génération et téléchargement d'attestation
  - Suivi du statut de souscription

### 4. **RENOUVELER UN CONTRAT**
- **Page**: `/contrats/:id/renouveler`
- **Fonctionnalités**:
  - Renouvellement de contrats existants
  - Mise à jour des informations si nécessaire
  - Processus de paiement pour le renouvellement
  - Génération de nouvelle attestation

### 5. **declarer un sinistre**
- **Pages**: `/sinistres` et `/sinistres/declarer`
- **Fonctionnalités**:
  - Formulaire de déclaration de sinistre
  - Types de sinistres (collision, vol, incendie, bris, autre)
  - Upload de documents
  - Suivi du statut de traitement
  - Historique des sinistres

### 6. **consulter ses contrats**
- **Pages**: `/contrats` et `/contrats/:id`
- **Fonctionnalités**:
  - Liste de tous les contrats
  - Détails complets de chaque contrat
  - Actions rapides (télécharger attestation, renouveler)
  - Filtres et recherche
  - Statuts en temps réel

## 🏠 Pages Principales

### Dashboard Client (`/dashboard`)
- **Vue d'ensemble** des statistiques personnelles
- **Contrats récents** avec actions rapides
- **Sinistres récents** avec statuts
- **Actions rapides** pour les tâches principales
- **Navigation** vers toutes les fonctionnalités

### Formulaire Devis (`/devis/nouveau`)
- **Interface multi-étapes** intuitive
- **Validation en temps réel** des champs
- **Sélection visuelle** des garanties et compagnies
- **Résumé** des sélections avant soumission
- **Design responsive** pour mobile

### Résultats Devis (`/devis/resultat/:id`)
- **Comparaison** des propositions des compagnies
- **Détails** des garanties incluses
- **Prix** détaillés (prime nette, taxes, total)
- **Actions** (accepter, refuser, modifier)
- **Validité** des devis

## 🎨 Design System

### Couleurs
- **Bleu principal**: `#1e3a8a` (KDS)
- **Orange accent**: `#f97316` (Assur)
- **Blanc**: `#ffffff`
- **Gris clair**: `#f8fafc`
- **Gris foncé**: `#64748b`

### Typographie
- **Titres**: Font-weight 700, tailles 1.5rem à 2.5rem
- **Corps**: Font-weight 400, taille 1rem
- **Labels**: Font-weight 600, taille 0.9rem

### Composants
- **Boutons**: Styles cohérents avec états hover/active
- **Formulaires**: Validation visuelle et messages d'erreur
- **Cartes**: Ombres subtiles et bordures arrondies
- **Navigation**: Menu responsive avec états actifs

## 📱 Responsive Design

### Breakpoints
- **Desktop**: ≥1024px
- **Tablet**: 768px - 1023px
- **Mobile**: <768px
- **Small Mobile**: <480px

### Adaptations Mobile
- **Menu hamburger** pour navigation
- **Formulaires** optimisés pour tactile
- **Cartes** empilées verticalement
- **Boutons** plus grands pour le tactile
- **Grilles** adaptatives

## 🔧 Architecture Technique

### Frontend (Angular 17)
- **Composants standalone** pour modularité
- **Services** pour la logique métier
- **Interfaces TypeScript** pour la sécurité des types
- **Routing** avec guards d'authentification
- **HTTP Interceptors** pour les tokens

### Backend (Laravel)
- **API RESTful** avec Sanctum
- **Validation** côté serveur
- **Relations Eloquent** pour les données
- **Middleware** d'authentification
- **Migrations** pour la base de données

## 🚀 Démarrage Rapide

### 1. Démarrer les serveurs
```powershell
.\start-client-interface.ps1
```

### 2. Accéder à l'interface
- **Page d'accueil**: http://localhost:4200
- **Connexion**: http://localhost:4200/login
- **Dashboard**: http://localhost:4200/dashboard

### 3. Tester le workflow complet
1. Créer un compte ou se connecter
2. Accéder au dashboard
3. Cliquer sur "Nouveau Devis"
4. Remplir le formulaire en 3 étapes
5. Consulter les résultats
6. Tester les autres fonctionnalités

## 📊 Fonctionnalités Avancées

### Gestion des États
- **Loading states** avec spinners
- **Error handling** avec messages utilisateur
- **Success feedback** avec confirmations
- **Empty states** avec actions suggérées

### Sécurité
- **Authentification** avec tokens
- **Validation** côté client et serveur
- **Protection des routes** sensibles
- **Sanitisation** des données

### Performance
- **Lazy loading** des composants
- **Optimisation** des requêtes API
- **Caching** des données statiques
- **Compression** des assets

## 🔄 Workflow Utilisateur Complet

1. **Inscription/Connexion** → Création du compte client
2. **Dashboard** → Vue d'ensemble et actions rapides
3. **Nouveau Devis** → Processus en 3 étapes
4. **Résultats Devis** → Comparaison des propositions
5. **Souscription** → Paiement et création du contrat
6. **Attestation** → Téléchargement du document
7. **Gestion Contrats** → Suivi et renouvellement
8. **Déclaration Sinistres** → Processus de réclamation

## ✅ Conformité UML

L'interface respecte parfaitement le diagramme de cas d'usage UML :
- ✅ Tous les cas d'usage implémentés
- ✅ Relations <<include>> et <<extend>> respectées
- ✅ Acteurs correctement représentés
- ✅ Workflow utilisateur conforme
- ✅ Intégration des systèmes externes

## 🎯 Prochaines Étapes

- [ ] Tests automatisés (unitaires et e2e)
- [ ] Optimisation des performances
- [ ] Intégration de nouvelles fonctionnalités
- [ ] Amélioration de l'accessibilité
- [ ] Documentation utilisateur détaillée


