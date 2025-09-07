# 🐳 Guide Déploiement Docker Hub Direct

## Prérequis
- Windows 10/11
- Git
- Compte Docker Hub
- Compte GitHub
- Compte Render
- Compte SonarCloud

## Configuration

### 1. Docker Hub
- Créez un compte sur https://hub.docker.com
- Créez un token d'accès (Settings → Security → New Access Token)
- Notez votre nom d'utilisateur Docker Hub

### 2. SonarCloud
- Allez sur https://sonarcloud.io
- Connectez votre compte GitHub
- Créez un projet pour MEMOIRE2
- Récupérez le token SONAR_TOKEN

### 3. Render
- Allez sur https://render.com
- Créez un compte
- Créez un nouveau service web
- Récupérez le SERVICE_ID et API_KEY

### 4. GitHub Secrets
- Allez sur votre repository GitHub
- Settings → Secrets and variables → Actions
- Ajoutez :
  - `DOCKERHUB_USERNAME` (votre nom d'utilisateur Docker Hub)
  - `DOCKERHUB_TOKEN` (votre token Docker Hub)
  - `SONAR_TOKEN` (votre token SonarCloud)
  - `RENDER_SERVICE_ID` (ID du service Render)
  - `RENDER_API_KEY` (clé API Render)

## Déploiement

### 1. Cloner le projet
```powershell
git clone https://github.com/smd30/MEMOIRE2.git
cd MEMOIRE2
```

### 2. Déployer
```powershell
.\scripts\deploy-docker-hub-direct.ps1
```

### 3. Monitorer
```powershell
.\scripts\monitor-docker-hub-direct.ps1
```

## Services Disponibles

- **GitHub Actions**: https://github.com/smd30/MEMOIRE2/actions
- **Docker Hub**: https://hub.docker.com/u/smd30
- **SonarCloud**: https://sonarcloud.io/project/overview?id=memoire2
- **Render**: https://render.com
- **Application**: https://memoire2-backend.onrender.com

## Avantages

✅ **Pas de Docker Desktop requis**
✅ **Build automatique sur GitHub**
✅ **Images Docker réutilisables**
✅ **Déploiement dans le cloud**
✅ **Analyse de code automatique**
✅ **Pipeline CI/CD complet**
✅ **Monitoring intégré**

## Commandes Utiles

```powershell
# Vérifier le statut
git status

# Pousser le code
git add .
git commit -m "🐳 Mise à jour"
git push origin main

# Monitoring
.\scripts\monitor-docker-hub-direct.ps1

# Voir les logs GitHub Actions
# Allez sur https://github.com/smd30/MEMOIRE2/actions
```

## Flux du Pipeline

1. **Code Push** → GitHub Actions se déclenche
2. **Build** → Images Docker construites sur GitHub
3. **Push** → Images poussées vers Docker Hub
4. **Test** → Tests exécutés avec les images Docker Hub
5. **Sonar** → Analyse de code avec SonarCloud
6. **Deploy** → Déploiement sur Render
7. **Monitor** → Surveillance des services

## Résolution de Problèmes

### Problème : Pipeline échoue
- Vérifiez les secrets GitHub
- Vérifiez les permissions Docker Hub
- Vérifiez les logs GitHub Actions

### Problème : Images non trouvées
- Vérifiez que Docker Hub est accessible
- Vérifiez les noms d'images
- Vérifiez les permissions

### Problème : Déploiement échoue
- Vérifiez la configuration Render
- Vérifiez les variables d'environnement
- Vérifiez les logs Render
