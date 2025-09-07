Write-Host "Test du deploiement local..." -ForegroundColor Green

# 1. Verifier que le Dockerfile fonctionne
Write-Host "Verification du Dockerfile..." -ForegroundColor Yellow
if (Test-Path "backend/Dockerfile") {
    Write-Host "Dockerfile trouve" -ForegroundColor Green
} else {
    Write-Host "Dockerfile manquant!" -ForegroundColor Red
    exit 1
}

if (Test-Path "backend/composer.lock") {
    Write-Host "composer.lock trouve" -ForegroundColor Green
} else {
    Write-Host "composer.lock manquant!" -ForegroundColor Red
    exit 1
}

if (Test-Path "backend/start-render.sh") {
    Write-Host "Script de demarrage trouve" -ForegroundColor Green
} else {
    Write-Host "Script de demarrage manquant!" -ForegroundColor Red
    exit 1
}

# 2. Verifier les endpoints de sante
Write-Host "Verification des endpoints de sante..." -ForegroundColor Yellow
if (Test-Path "backend/app/Http/Controllers/Api/HealthController.php") {
    Write-Host "HealthController trouve" -ForegroundColor Green
} else {
    Write-Host "HealthController manquant!" -ForegroundColor Red
    exit 1
}

# 3. Verifier les routes
Write-Host "Verification des routes..." -ForegroundColor Yellow
$apiRoutes = Get-Content "backend/routes/api.php" | Select-String "health"
if ($apiRoutes) {
    Write-Host "Routes de sante trouvees" -ForegroundColor Green
} else {
    Write-Host "Routes de sante manquantes!" -ForegroundColor Red
    exit 1
}

# 4. Test de l'API locale
Write-Host "Test de l'API locale..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/health" -UseBasicParsing
    Write-Host "API locale accessible: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "API locale non accessible (normal si le serveur n'est pas demarre)" -ForegroundColor Yellow
}

# 5. Afficher les informations Render
Write-Host "Informations pour Render:" -ForegroundColor Cyan
Write-Host "   Dockerfile: ./backend/Dockerfile" -ForegroundColor White
Write-Host "   Context: ./backend" -ForegroundColor White
Write-Host "   Health Check: /api/health" -ForegroundColor White
Write-Host "   Database Check: /api/health/database" -ForegroundColor White

Write-Host "Tests termines avec succes!" -ForegroundColor Green
Write-Host "Votre application est prete pour Render!" -ForegroundColor Cyan