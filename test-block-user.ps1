# Test de l'API de blocage d'utilisateur
Write-Host "🧪 Test de l'API de blocage d'utilisateur..." -ForegroundColor Cyan

# Étape 1: Connexion pour obtenir un token
$loginData = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    Write-Host "🔐 Connexion..." -ForegroundColor Blue
    $loginResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/login" -Method POST -Body $loginData -ContentType "application/json"
    $loginResult = $loginResponse.Content | ConvertFrom-Json
    
    if ($loginResult.success) {
        $token = $loginResult.data.token
        Write-Host "✅ Connexion réussie !" -ForegroundColor Green
        
        # Étape 2: Récupérer la liste des utilisateurs
        Write-Host "👥 Récupération des utilisateurs..." -ForegroundColor Blue
        $headers = @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
        }
        
        $usersResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/users" -Method GET -Headers $headers
        $usersResult = $usersResponse.Content | ConvertFrom-Json
        
        if ($usersResult.success -and $usersResult.data.Count -gt 0) {
            $firstUser = $usersResult.data[0]
            Write-Host "✅ Utilisateur trouvé: $($firstUser.prenom) $($firstUser.nom) (ID: $($firstUser.id))" -ForegroundColor Green
            
            # Étape 3: Tester le blocage
            Write-Host "🔒 Test de blocage..." -ForegroundColor Blue
            $blockData = @{
                status = "bloque"
            } | ConvertTo-Json
            
            $blockResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/users/$($firstUser.id)/toggle-status" -Method PUT -Body $blockData -Headers $headers -ContentType "application/json"
            $blockResult = $blockResponse.Content | ConvertFrom-Json
            
            if ($blockResult.success) {
                Write-Host "✅ Utilisateur bloqué avec succès !" -ForegroundColor Green
            } else {
                Write-Host "❌ Erreur lors du blocage: $($blockResult.message)" -ForegroundColor Red
            }
            
        } else {
            Write-Host "❌ Aucun utilisateur trouvé" -ForegroundColor Red
        }
        
    } else {
        Write-Host "❌ Erreur de connexion: $($loginResult.message)" -ForegroundColor Red
    }
    
} catch {
    Write-Host "❌ Erreur lors du test: $($_.Exception.Message)" -ForegroundColor Red
}





