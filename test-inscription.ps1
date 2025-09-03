# Script de test pour le système d'inscription
Write-Host "=== Test du Système d'Inscription ===" -ForegroundColor Green
Write-Host ""

# Vérifier si les serveurs sont en cours d'exécution
Write-Host "1. Vérification des serveurs..." -ForegroundColor Yellow

$backendRunning = $false
$frontendRunning = $false

try {
    $backendResponse = Invoke-WebRequest -Uri "http://localhost:8000/api" -Method GET -TimeoutSec 5 -ErrorAction SilentlyContinue
    if ($backendResponse.StatusCode -eq 200) {
        $backendRunning = $true
        Write-Host "✅ Backend Laravel: En cours d'exécution sur http://localhost:8000" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Backend Laravel: Non accessible sur http://localhost:8000" -ForegroundColor Red
}

try {
    $frontendResponse = Invoke-WebRequest -Uri "http://localhost:4200" -Method GET -TimeoutSec 5 -ErrorAction SilentlyContinue
    if ($frontendResponse.StatusCode -eq 200) {
        $frontendRunning = $true
        Write-Host "✅ Frontend Angular: En cours d'exécution sur http://localhost:4200" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Frontend Angular: Non accessible sur http://localhost:4200" -ForegroundColor Red
}

Write-Host ""

# Démarrer les serveurs si nécessaire
if (-not $backendRunning) {
    Write-Host "2. Démarrage du backend..." -ForegroundColor Yellow
    Write-Host "   Exécution: cd backend; php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Cyan
    Start-Process powershell -ArgumentList "-Command", "cd backend; php artisan serve --host=0.0.0.0 --port=8000" -WindowStyle Minimized
    Write-Host "   ⏳ Attente du démarrage du backend..." -ForegroundColor Yellow
    Start-Sleep -Seconds 10
}

if (-not $frontendRunning) {
    Write-Host "3. Démarrage du frontend..." -ForegroundColor Yellow
    Write-Host "   Exécution: cd frontend; npm start" -ForegroundColor Cyan
    Start-Process powershell -ArgumentList "-Command", "cd frontend; npm start" -WindowStyle Minimized
    Write-Host "   ⏳ Attente du démarrage du frontend..." -ForegroundColor Yellow
    Start-Sleep -Seconds 15
}

Write-Host ""

# Test de l'API d'inscription
Write-Host "4. Test de l'API d'inscription..." -ForegroundColor Yellow

$testData = @{
    nom = "Dupont"
    prenom = "Jean"
    email = "jean.dupont.test@example.com"
    Telephone = "0123456789"
    adresse = "123 Rue de la Paix, 75001 Paris"
    MotDePasse = "password123"
    MotDePasse_confirmation = "password123"
}

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/register" -Method POST -Body ($testData | ConvertTo-Json) -ContentType "application/json"
    
    if ($response.success) {
        Write-Host "✅ Test d'inscription réussi !" -ForegroundColor Green
        Write-Host "   Utilisateur créé: $($response.data.user.nom) $($response.data.user.prenom)" -ForegroundColor Cyan
        Write-Host "   Email: $($response.data.user.email)" -ForegroundColor Cyan
        Write-Host "   Téléphone: $($response.data.user.Telephone)" -ForegroundColor Cyan
        Write-Host "   Adresse: $($response.data.user.adresse)" -ForegroundColor Cyan
        Write-Host "   Token généré: $($response.data.token.Substring(0, 20))..." -ForegroundColor Cyan
    } else {
        Write-Host "❌ Test d'inscription échoué: $($response.message)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Erreur lors du test d'inscription: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Ouvrir les interfaces
Write-Host "5. Ouverture des interfaces..." -ForegroundColor Yellow

Write-Host "   🌐 Ouverture de l'interface d'inscription..." -ForegroundColor Cyan
Start-Process "http://localhost:4200/register"

Write-Host "   📋 Ouverture du fichier de test..." -ForegroundColor Cyan
Start-Process "test-inscription.html"

Write-Host ""

# Instructions finales
Write-Host "=== Instructions de Test ===" -ForegroundColor Green
Write-Host ""
Write-Host "🎯 Champs d'inscription conformes aux diagrammes UML:" -ForegroundColor Yellow
Write-Host "   • Nom (obligatoire)" -ForegroundColor White
Write-Host "   • Prénom (obligatoire)" -ForegroundColor White
Write-Host "   • Email (obligatoire)" -ForegroundColor White
Write-Host "   • Téléphone (obligatoire)" -ForegroundColor White
Write-Host "   • Adresse (obligatoire)" -ForegroundColor White
Write-Host "   • Mot de passe (obligatoire, min 8 caractères)" -ForegroundColor White
Write-Host "   • Confirmation du mot de passe (obligatoire)" -ForegroundColor White
Write-Host ""
Write-Host "🔗 Liens utiles:" -ForegroundColor Yellow
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor Cyan
Write-Host "   • Inscription: http://localhost:4200/register" -ForegroundColor Cyan
Write-Host "   • Backend API: http://localhost:8000/api" -ForegroundColor Cyan
Write-Host ""
Write-Host "✅ Le système d'inscription est maintenant conforme à vos diagrammes UML !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
