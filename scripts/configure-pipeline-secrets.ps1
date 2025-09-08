Write-Host "Configuration des secrets GitHub pour le pipeline DevOps..." -ForegroundColor Green

Write-Host ""
Write-Host "SECRETS REQUIS POUR LE PIPELINE:" -ForegroundColor Yellow
Write-Host ""

# Docker Hub
Write-Host "1. DOCKER HUB:" -ForegroundColor Cyan
Write-Host "   - Allez sur: https://hub.docker.com" -ForegroundColor White
Write-Host "   - Creer un compte gratuit" -ForegroundColor White
Write-Host "   - Settings > Security > New Access Token" -ForegroundColor White
Write-Host "   - Permissions: Read, Write, Delete" -ForegroundColor White
Write-Host "   - Secret: DOCKERHUB_USERNAME (votre nom d'utilisateur)" -ForegroundColor Green
Write-Host "   - Secret: DOCKERHUB_TOKEN (votre token)" -ForegroundColor Green
Write-Host ""

# SonarCloud
Write-Host "2. SONARCLOUD:" -ForegroundColor Cyan
Write-Host "   - Allez sur: https://sonarcloud.io" -ForegroundColor White
Write-Host "   - Connectez avec GitHub" -ForegroundColor White
Write-Host "   - Analyze new project > GitHub > MEMOIRE2" -ForegroundColor White
Write-Host "   - Account > Security > Generate Tokens" -ForegroundColor White
Write-Host "   - Secret: SONAR_TOKEN (votre token)" -ForegroundColor Green
Write-Host ""

# Render
Write-Host "3. RENDER:" -ForegroundColor Cyan
Write-Host "   - Allez sur: https://render.com" -ForegroundColor White
Write-Host "   - Creer un compte gratuit" -ForegroundColor White
Write-Host "   - New + > Web Service > Connect GitHub > MEMOIRE2" -ForegroundColor White
Write-Host "   - Account Settings > API Keys > Create API Key" -ForegroundColor White
Write-Host "   - Secret: RENDER_SERVICE_ID (ID du service)" -ForegroundColor Green
Write-Host "   - Secret: RENDER_API_KEY (votre clé API)" -ForegroundColor Green
Write-Host "   - Secret: RENDER_SERVICE_URL (URL du service)" -ForegroundColor Green
Write-Host ""

Write-Host "CONFIGURATION GITHUB:" -ForegroundColor Yellow
Write-Host "1. Allez sur: https://github.com/smd30/MEMOIRE2/settings/secrets/actions" -ForegroundColor White
Write-Host "2. Cliquez sur 'New repository secret'" -ForegroundColor White
Write-Host "3. Ajoutez chaque secret ci-dessus" -ForegroundColor White
Write-Host ""

Write-Host "SECRETS A AJOUTER:" -ForegroundColor Red
Write-Host "DOCKERHUB_USERNAME" -ForegroundColor White
Write-Host "DOCKERHUB_TOKEN" -ForegroundColor White
Write-Host "SONAR_TOKEN" -ForegroundColor White
Write-Host "RENDER_SERVICE_ID" -ForegroundColor White
Write-Host "RENDER_API_KEY" -ForegroundColor White
Write-Host "RENDER_SERVICE_URL" -ForegroundColor White
Write-Host ""

Write-Host "TEST DU PIPELINE:" -ForegroundColor Yellow
Write-Host "1. Une fois tous les secrets configures" -ForegroundColor White
Write-Host "2. Poussez le code: git push origin main" -ForegroundColor White
Write-Host "3. Allez sur: https://github.com/smd30/MEMOIRE2/actions" -ForegroundColor White
Write-Host "4. Verifiez que le pipeline 'Pipeline DevOps Complet' s'execute" -ForegroundColor White
Write-Host ""

Write-Host "PIPELINE COMPLET:" -ForegroundColor Green
Write-Host "✅ Tests Backend et Frontend" -ForegroundColor White
Write-Host "✅ Build et Push Docker Images" -ForegroundColor White
Write-Host "✅ Analyse SonarCloud" -ForegroundColor White
Write-Host "✅ Deploiement Render" -ForegroundColor White
Write-Host "✅ Tests de deploiement" -ForegroundColor White
Write-Host "✅ Notifications" -ForegroundColor White
