# 🚀 Démarrage Rapide - Plateforme d'Assurance Automobile

## 📋 Prérequis

### Logiciels requis
- **PHP 8.2+** : https://windows.php.net/download/
- **Composer** : https://getcomposer.org/download/
- **Node.js 18+** : https://nodejs.org/
- **MySQL 8.0+** : https://dev.mysql.com/downloads/mysql/

### Configuration MySQL
1. Installez MySQL
2. Créez un utilisateur root avec le mot de passe `passer`
3. Créez une base de données `assurance_auto`

```sql
CREATE DATABASE assurance_auto;
```

## 🚀 Démarrage automatique (Recommandé)

### Option 1 : Script automatique
Double-cliquez sur `start-all.bat` à la racine du projet.

### Option 2 : Démarrage manuel

#### Backend Laravel
```bash
cd backend
start.bat
```

#### Frontend Angular
```bash
cd frontend
start.bat
```

## 🔐 Compte de test

**Email :** `test@example.com`  
**Mot de passe :** `password`

## 📍 URLs d'accès

- **Frontend** : http://localhost:4200
- **Backend API** : http://localhost:8000/api
- **Health Check** : http://localhost:8000/api/health

## 🧪 Test de connexion

### Via Postman
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password"
}
```

### Réponse attendue
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

## 🔧 Fonctionnalités disponibles

### Frontend Angular
- ✅ Page d'accueil avec QR code
- ✅ Connexion/Inscription
- ✅ Gestion des véhicules
- ✅ Devis en ligne
- ✅ Contrats d'assurance
- ✅ Déclaration de sinistres
- ✅ Interface gestionnaire
- ✅ Interface administrateur

### Backend Laravel
- ✅ Authentification Sanctum
- ✅ API REST complète
- ✅ Gestion des utilisateurs
- ✅ Calcul des devis
- ✅ Gestion des contrats
- ✅ Déclaration de sinistres
- ✅ Système de rôles
- ✅ Base de données MySQL

## 🐛 Résolution de problèmes

### Erreur "Could not open input file: artisan"
- Assurez-vous d'être dans le dossier `backend`
- Vérifiez que PHP est installé et dans le PATH

### Erreur "Error: This command is not available when running the Angular CLI outside a workspace"
- Assurez-vous d'être dans le dossier `frontend`
- Vérifiez que Node.js et npm sont installés

### Erreur de connexion à la base de données
- Vérifiez que MySQL est démarré
- Vérifiez les paramètres de connexion dans `.env`
- Créez la base de données `assurance_auto`

### Erreur "Call to undefined method App\Models\User::tokens()"
- Sanctum n'est pas installé
- Exécutez : `composer require laravel/sanctum`

## 📞 Support

Si vous rencontrez des problèmes :
1. Vérifiez que tous les prérequis sont installés
2. Consultez les logs dans les fenêtres des serveurs
3. Testez l'API directement via Postman

## 🎯 Prochaines étapes

Une fois l'application démarrée :
1. Connectez-vous avec le compte test
2. Explorez les différentes fonctionnalités
3. Testez la création de devis
4. Découvrez l'interface gestionnaire et admin
