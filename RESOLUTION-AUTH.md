# 🔐 Résolution du Problème d'Authentification - Module Devis

## ❌ Problème Rencontré
```
Failed to load resource: the server responded with a status of 401 (Unauthorized)
```

## ✅ Solution

### 1. **Démarrer le serveur backend**
```powershell
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. **Créer un utilisateur de test**
```powershell
cd backend
php test-auth.php
```

### 3. **Se connecter avec les identifiants de test**
- **Email** : `client@test.com`
- **Mot de passe** : `password123`

### 4. **Accéder au module devis**
1. Aller sur http://localhost:4200
2. Cliquer sur "Se Connecter"
3. Utiliser les identifiants ci-dessus
4. Cliquer sur "DEVIS" dans le menu
5. Cliquer sur "Nouveau devis"

## 🚀 Script Automatique

Pour tout faire en une fois :
```powershell
.\start-and-test-devis.ps1
```

## 📋 Vérification

### Test de l'API
```powershell
cd backend
php test-devis-complet.php
```

### Test Frontend
1. Ouvrir http://localhost:4200
2. Se connecter avec `client@test.com` / `password123`
3. Aller dans "DEVIS" → "Nouveau devis"
4. Remplir le formulaire multi-étapes

## 🔧 Dépannage

### Si l'erreur 401 persiste :
1. Vérifier que le serveur backend fonctionne : http://localhost:8000
2. Vérifier que l'utilisateur est bien connecté
3. Vérifier les tokens dans le localStorage du navigateur

### Si le serveur ne démarre pas :
1. Vérifier que PHP est installé : `php --version`
2. Vérifier que Composer est installé : `composer --version`
3. Installer les dépendances : `composer install`

## 📞 Support

- **Logs Laravel** : `backend/storage/logs/laravel.log`
- **Console Angular** : F12 → Console
- **Network** : F12 → Network pour voir les requêtes API


