Write-Host "📊 Monitoring Docker Hub Direct..." -ForegroundColor Green

# 1. Vérifier le statut GitHub Actions
Write-Host "🔍 Vérification du statut GitHub Actions..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://api.github.com/repos/smd30/MEMOIRE2/actions/runs" -UseBasicParsing
    $runs = $response.Content | ConvertFrom-Json
    $latestRun = $runs.workflow_runs[0]
    Write-Host "✅ Dernière exécution: $($latestRun.status) - $($latestRun.conclusion)" -ForegroundColor Green
} catch {
    Write-Host "❌ Impossible de vérifier GitHub Actions" -ForegroundColor Red
}

# 2. Vérifier Docker Hub
Write-Host "🐳 Vérification Docker Hub..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://hub.docker.com/v2/repositories/smd30/memoire2-backend/" -UseBasicParsing
    Write-Host "✅ Image Backend disponible sur Docker Hub" -ForegroundColor Green
} catch {
    Write-Host "❌ Image Backend non trouvée sur Docker Hub" -ForegroundColor Red
}

try {
    $response = Invoke-WebRequest -Uri "https://hub.docker.com/v2/repositories/smd30/memoire2-frontend/" -UseBasicParsing
    Write-Host "✅ Image Frontend disponible sur Docker Hub" -ForegroundColor Green
} catch {
    Write-Host "❌ Image Frontend non trouvée sur Docker Hub" -ForegroundColor Red
}

# 3. Vérifier SonarCloud
Write-Host "🔍 Vérification SonarCloud..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://sonarcloud.io/api/project_analyses/search?project=memoire2" -UseBasicParsing
    Write-Host "✅ Projet SonarCloud accessible" -ForegroundColor Green
} catch {
    Write-Host "❌ Projet SonarCloud non accessible" -ForegroundColor Red
}

# 4. Vérifier Render
Write-Host "☁️ Vérification Render..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://memoire2-backend.onrender.com" -UseBasicParsing
    Write-Host "✅ Application Render accessible" -ForegroundColor Green
} catch {
    Write-Host "❌ Application Render non accessible" -ForegroundColor Red
}

# 5. Afficher les liens
Write-Host "🔗 Liens de monitoring:" -ForegroundColor Cyan
Write-Host "   GitHub Actions: https://github.com/smd30/MEMOIRE2/actions" -ForegroundColor White
Write-Host "   Docker Hub: https://hub.docker.com/u/smd30" -ForegroundColor White
Write-Host "   SonarCloud: https://sonarcloud.io/project/overview?id=memoire2" -ForegroundColor White
Write-Host "   Render: https://render.com" -ForegroundColor White

Write-Host "✅ Monitoring Docker Hub Direct terminé!" -ForegroundColor Green
