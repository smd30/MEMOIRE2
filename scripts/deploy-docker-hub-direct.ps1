Write-Host "🐳 Déploiement Docker Hub Direct..." -ForegroundColor Green

# 1. Vérifier les prérequis
Write-Host "📦 Vérification des prérequis..." -ForegroundColor Yellow
if (!(Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Git n'est pas installé!" -ForegroundColor Red
    exit 1
}

if (!(Get-Command curl -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Curl n'est pas installé!" -ForegroundColor Red
    exit 1
}

# 2. Vérifier la connexion GitHub
Write-Host "🔗 Vérification de la connexion GitHub..." -ForegroundColor Yellow
try {
    git remote -v
    Write-Host "✅ Repository GitHub configuré" -ForegroundColor Green
} catch {
    Write-Host "❌ Repository GitHub non configuré!" -ForegroundColor Red
    exit 1
}

# 3. Vérifier les secrets GitHub
Write-Host "🔐 Vérification des secrets GitHub..." -ForegroundColor Yellow
Write-Host "📝 Assurez-vous d'avoir configuré ces secrets dans GitHub:" -ForegroundColor Cyan
Write-Host "   - DOCKERHUB_USERNAME" -ForegroundColor White
Write-Host "   - DOCKERHUB_TOKEN" -ForegroundColor White
Write-Host "   - SONAR_TOKEN" -ForegroundColor White
Write-Host "   - RENDER_SERVICE_ID" -ForegroundColor White
Write-Host "   - RENDER_API_KEY" -ForegroundColor White

# 4. Pousser le code pour déclencher le pipeline
Write-Host "📤 Poussée du code vers GitHub..." -ForegroundColor Yellow
git add .
git commit -m "🐳 Pipeline Docker Hub Direct"
git push origin main

# 5. Afficher les liens de vérification
Write-Host "🔗 Liens de vérification:" -ForegroundColor Cyan
Write-Host "   GitHub Actions: https://github.com/smd30/MEMOIRE2/actions" -ForegroundColor White
Write-Host "   Docker Hub: https://hub.docker.com/u/smd30" -ForegroundColor White
Write-Host "   SonarCloud: https://sonarcloud.io/project/overview?id=memoire2" -ForegroundColor White
Write-Host "   Render: https://render.com" -ForegroundColor White

Write-Host "🎉 Déploiement Docker Hub Direct terminé!" -ForegroundColor Green
Write-Host "⏳ Le pipeline GitHub Actions va maintenant:" -ForegroundColor Yellow
Write-Host "   1. Construire les images Docker" -ForegroundColor White
Write-Host "   2. Les pousser vers Docker Hub" -ForegroundColor White
Write-Host "   3. Exécuter les tests" -ForegroundColor White
Write-Host "   4. Analyser avec SonarCloud" -ForegroundColor White
Write-Host "   5. Déployer sur Render" -ForegroundColor White
