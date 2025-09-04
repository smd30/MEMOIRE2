# Module Devis - Assurance Automobile

## 📋 Description

Le module devis permet aux clients de demander des devis d'assurance automobile en ligne. Il comprend un formulaire multi-étapes, une simulation de tarification et une gestion complète des devis.

## 🚀 Fonctionnalités

### ✅ Formulaire Multi-étapes
- **Étape 1** : Informations du véhicule (catégorie, marque, modèle, immatriculation, etc.)
- **Étape 2** : Sélection de la compagnie d'assurance et période
- **Étape 3** : Choix des garanties (RC obligatoire + garanties optionnelles)
- **Étape 4** : Simulation et soumission du devis

### ✅ Gestion des Devis
- Liste des devis de l'utilisateur
- Filtrage par statut
- Actions : voir, accepter, rejeter, supprimer
- Indicateurs visuels (expiré, statut)

### ✅ API Backend
- Simulation de tarification
- Gestion des compagnies et garanties
- Upload de carte grise
- Authentification requise

## 🏗️ Architecture

### Backend (Laravel)
```
app/
├── Http/Controllers/Api/DevisController.php
├── Models/
│   ├── Compagnie.php
│   ├── Garantie.php
│   ├── Vehicule.php
│   └── Devis.php
└── database/
    ├── migrations/
    └── seeders/DevisSeeder.php
```

### Frontend (Angular)
```
src/app/components/
├── devis/
│   └── devis.component.ts          # Formulaire multi-étapes
└── devis-list/
    └── devis-list.component.ts     # Liste des devis
```

## 🔧 Installation et Configuration

### 1. Base de données
```bash
cd backend
php artisan migrate
php artisan db:seed --class=DevisSeeder
```

### 2. Démarrage des serveurs
```bash
# Option 1: Script PowerShell
.\start-servers.ps1

# Option 2: Manuel
# Terminal 1 - Backend
cd backend
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - Frontend
cd frontend
ng serve
```

## 📊 Données de Test

### Compagnies d'Assurance
1. **AssurAuto Sénégal**
   - Spécialiste de l'assurance automobile
   - Commission: 12.5%

2. **SécuriVie Assurance**
   - Assurance innovante et personnalisée
   - Commission: 15%

### Garanties Disponibles
- **Responsabilité Civile** (Obligatoire)
- Vol
- Incendie
- Bris de glace
- Défense et recours
- Assistance dépannage
- Protection conducteur
- Catastrophes naturelles

## 🔌 API Endpoints

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/devis/create` | Données initiales (compagnies, périodes) |
| GET | `/api/devis/garanties/{compagnieId}` | Garanties par compagnie |
| POST | `/api/devis/simuler` | Simulation de devis |
| POST | `/api/devis` | Création de devis |
| GET | `/api/devis` | Liste des devis |
| GET | `/api/devis/{id}` | Détails d'un devis |
| POST | `/api/devis/{id}/accepter` | Accepter un devis |
| POST | `/api/devis/{id}/rejeter` | Rejeter un devis |
| DELETE | `/api/devis/{id}` | Supprimer un devis |

## 🎨 Interface Utilisateur

### Thème de Couleurs
- **Principal** : #151C46 (bleu foncé)
- **Accent** : #ff6b35 (orange)
- **Fond** : Blanc

### Composants
- **Formulaire multi-étapes** avec navigation
- **Validation en temps réel**
- **Upload de fichiers** (carte grise)
- **Simulation interactive**
- **Liste responsive** des devis

## 🔐 Sécurité

- **Authentification** : Tous les endpoints protégés
- **Validation** : Client et serveur
- **Upload sécurisé** : Types de fichiers limités
- **CSRF Protection** : Laravel

## 📱 Responsive Design

- **Desktop** : Interface complète
- **Tablet** : Adaptation des grilles
- **Mobile** : Formulaire en colonne unique

## 🧪 Tests

### Test de l'API
```bash
cd backend
php test_api.php
```

### Test Frontend
```bash
cd frontend
ng test
```

## 📝 Utilisation

1. **Connexion** : L'utilisateur doit être connecté
2. **Accès** : Menu "DEVIS" dans la navigation
3. **Création** : Cliquer sur "Nouveau devis"
4. **Remplissage** : Suivre les 4 étapes
5. **Simulation** : Voir le devis calculé
6. **Soumission** : Accepter ou rejeter le devis

## 🚨 Points Importants

- **RC Obligatoire** : La Responsabilité Civile est automatiquement sélectionnée
- **Expiration** : Les devis expirent après 30 jours
- **Validation** : Tous les champs obligatoires sont validés
- **Upload** : Carte grise acceptée en PDF ou image

## 🔄 Workflow

```
Utilisateur → Formulaire → Simulation → Devis → Acceptation/Rejet → Contrat
```

## 📞 Support

Pour toute question ou problème :
- Vérifier les logs Laravel : `storage/logs/laravel.log`
- Vérifier la console Angular : F12 → Console
- Tester l'API directement avec Postman ou curl
