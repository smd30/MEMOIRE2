# 🚨 Dépannage Rapide - Erreur 401 Unauthorized

## ❌ **Problème**
```
Failed to load resource: the server responded with a status of 401 (Unauthorized)
```

## ✅ **Solution Immédiate**

### **Option 1 : Connexion Manuelle (Recommandée)**
1. **Retournez à l'accueil** : http://localhost:4200
2. **Cliquez sur "Se Connecter"**
3. **Utilisez ces identifiants** :
   - Email : `client@test.com`
   - Mot de passe : `password123`
4. **Cliquez sur "Se connecter"**
5. **Puis allez dans "DEVIS"**

### **Option 2 : Connexion Automatique (Script)**
1. **Ouvrez la console** (F12 → Console)
2. **Copiez et collez ce script** :
```javascript
// Connexion automatique
fetch('http://localhost:8000/api/auth/login', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        email: 'client@test.com',
        password: 'password123'
    })
})
.then(r => r.json())
.then(data => {
    localStorage.setItem('user_session', JSON.stringify({
        user: data.data.user,
        token: data.data.token,
        expiresAt: Date.now() + 86400000,
        lastActivity: Date.now()
    }));
    window.location.reload();
});
```

### **Option 3 : Vérification des Serveurs**
```powershell
# Vérifier le backend
curl http://localhost:8000

# Vérifier le frontend  
curl http://localhost:4200
```

## 🔧 **Vérifications**

### **1. Serveur Backend**
- ✅ Port 8000 accessible
- ✅ API Laravel fonctionne
- ✅ Utilisateur `client@test.com` existe

### **2. Serveur Frontend**
- ✅ Port 4200 accessible
- ✅ Angular fonctionne
- ✅ Interface utilisateur chargée

### **3. Authentification**
- ❌ **Vous n'êtes pas connecté**
- ❌ **Token manquant dans localStorage**
- ❌ **Session expirée**

## 🎯 **Workflow Correct**

```
1. Accueil → 2. Se Connecter → 3. Identifiants → 4. Connexion → 5. DEVIS → 6. Nouveau devis
```

**PAS** : `Accueil → DEVIS` (cela cause l'erreur 401)

## 📞 **Support**

### **Logs utiles**
- **Console navigateur** : F12 → Console
- **Network** : F12 → Network
- **Local Storage** : F12 → Application → Local Storage

### **Test API direct**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"client@test.com","password":"password123"}'
```

## 🎉 **Résultat Attendu**

Après connexion :
- ✅ Plus d'erreur 401
- ✅ Formulaire devis chargé
- ✅ Compagnies et garanties disponibles
- ✅ Interface fonctionnelle


