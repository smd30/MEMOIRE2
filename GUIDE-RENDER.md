# 🚀 Guide Configuration Render - MEMOIRE2

## ✅ CORRECTIONS APPLIQUÉES

J'ai corrigé les problèmes suivants :

### **🔧 Dockerfile simplifié**
- ✅ Supprimé les références aux fichiers manquants (`docker/nginx.conf`, `docker/supervisord.conf`, `docker/start.sh`)
- ✅ Créé un Dockerfile optimisé pour Render
- ✅ Ajouté le fichier `composer.lock` manquant

### **🏥 Endpoints de santé**
- ✅ Créé `HealthController` pour Render
- ✅ Ajouté les routes `/api/health` et `/api/health/database`
- ✅ Script de démarrage `start-render.sh`

### **📁 Fichiers créés**
- ✅ `backend/Dockerfile` (simplifié)
- ✅ `backend/start-render.sh` (script de démarrage)
- ✅ `backend/app/Http/Controllers/Api/HealthController.php`
- ✅ `render-simple.yaml` (configuration Render)

---

## **🔧 ÉTAPES POUR CONFIGURER RENDER**

### **1. Aller sur Render**
- Allez sur : https://render.com
- Connectez-vous avec votre compte GitHub

### **2. Créer un nouveau service**
- Cliquez sur **"New +"**
- Sélectionnez **"Web Service"**
- Connectez votre repository **MEMOIRE2**

### **3. Configurer le service**
```
Name: memoire2-backend
Environment: Docker
Dockerfile Path: ./backend/Dockerfile
Docker Context: ./backend
Plan: Free
Region: Oregon (US West)
Branch: main
```

### **4. Variables d'environnement**
Ajoutez ces variables dans Render :

```
APP_ENV=production
APP_DEBUG=false
APP_NAME=MEMOIRE2
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=kdsassur@gmail.com
MAIL_PASSWORD=drta mgti ioxp hwwo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=kdsassur@gmail.com
MAIL_FROM_NAME=MEMOIRE2
```

### **5. Créer une base de données**
- Cliquez sur **"New +"**
- Sélectionnez **"PostgreSQL"** (ou MySQL si disponible)
- Nom : `memoire2-db`
- Plan : Free

### **6. Connecter la base de données**
- Retournez sur votre service web
- Allez dans **"Environment"**
- Ajoutez ces variables :
```
DB_CONNECTION=pgsql
DB_HOST=[host de votre DB]
DB_PORT=5432
DB_DATABASE=[nom de votre DB]
DB_USERNAME=[utilisateur de votre DB]
DB_PASSWORD=[mot de passe de votre DB]
```

### **7. Déployer**
- Cliquez sur **"Create Web Service"**
- Render va automatiquement :
  - Cloner votre repository
  - Construire l'image Docker
  - Démarrer le service

---

## **🔍 VÉRIFICATION DU DÉPLOIEMENT**

### **1. Vérifier les logs**
- Allez dans l'onglet **"Logs"** de votre service
- Vérifiez que le build se termine sans erreur

### **2. Tester les endpoints**
Une fois déployé, testez :
- **Santé générale** : `https://votre-service.onrender.com/api/health`
- **Base de données** : `https://votre-service.onrender.com/api/health/database`

### **3. Vérifier l'application**
- **API** : `https://votre-service.onrender.com/api`
- **Frontend** : Configurez l'URL de l'API dans votre frontend

---

## **📊 INFORMATIONS POUR GITHUB SECRETS**

Une fois Render configuré, notez :

```
RENDER_SERVICE_ID: [ID du service Render]
RENDER_API_KEY: [Clé API Render]
```

Ces informations seront nécessaires pour le pipeline GitHub Actions.

---

## **🎯 PROCHAINES ÉTAPES**

1. **Configurez Render** avec les étapes ci-dessus
2. **Testez le déploiement** 
3. **Notez le SERVICE_ID et API_KEY**
4. **Configurez les secrets GitHub**
5. **Activez le pipeline Docker Hub Direct**

---

## **❓ RÉSOLUTION DE PROBLÈMES**

### **Problème : Build échoue**
- Vérifiez que le Dockerfile est dans `./backend/Dockerfile`
- Vérifiez que `composer.lock` existe

### **Problème : Service ne démarre pas**
- Vérifiez les logs dans Render
- Vérifiez les variables d'environnement

### **Problème : Base de données**
- Vérifiez que la DB est créée
- Vérifiez les variables de connexion DB

---

**🚀 Votre application est maintenant prête pour Render !**

**Suivez les étapes ci-dessus pour déployer sur Render, puis nous configurerons les secrets GitHub.**
