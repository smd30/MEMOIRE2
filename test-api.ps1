# Test simple de l'API d'inscription
Write-Host "=== Test de l'API d'Inscription ===" -ForegroundColor Green
Write-Host ""

# Données de test
$testData = @{
    nom = "Diop"
    prenom = "Sokhna Mbaye"
    email = "diopsokhnambaye15@gmail.com"
    Telephone = "786136720"
    adresse = "PARCELLES ASSAINIES U26"
    MotDePasse = "password123"
    MotDePasse_confirmation = "password123"
}

Write-Host "Données de test:" -ForegroundColor Yellow
$testData | Format-Table

Write-Host ""
Write-Host "Envoi de la requête..." -ForegroundColor Yellow

try {
    $headers = @{
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    }
    
    $body = $testData | ConvertTo-Json
    
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/register" -Method POST -Headers $headers -Body $body
    
    Write-Host ""
    Write-Host "✅ SUCCÈS !" -ForegroundColor Green
    Write-Host "Message: $($response.message)" -ForegroundColor Cyan
    Write-Host "Utilisateur créé: $($response.data.user.nom) $($response.data.user.prenom)" -ForegroundColor Cyan
    Write-Host "Email: $($response.data.user.email)" -ForegroundColor Cyan
    Write-Host "Token: $($response.data.token.Substring(0, 20))..." -ForegroundColor Cyan
    
} catch {
    Write-Host ""
    Write-Host "❌ ERREUR !" -ForegroundColor Red
    Write-Host "Message: $($_.Exception.Message)" -ForegroundColor Red
    
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Réponse du serveur: $responseBody" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")





