# Test des API Admin
Write-Host "=== TEST DES API ADMIN ===" -ForegroundColor Green

# URL de base
$baseUrl = "http://localhost:8000/api"

# Test de connexion admin
Write-Host "`n1. Test de connexion admin..." -ForegroundColor Yellow
$loginData = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/auth/login" -Method POST -Body $loginData -ContentType "application/json"
    $token = $loginResponse.data.token
    Write-Host "✅ Connexion admin réussie" -ForegroundColor Green
    Write-Host "Token: $($token.Substring(0, 20))..." -ForegroundColor Gray
} catch {
    Write-Host "❌ Erreur de connexion admin: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Headers avec token
$headers = @{
    "Authorization" = "Bearer $token"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

# Test du dashboard admin
Write-Host "`n2. Test du dashboard admin..." -ForegroundColor Yellow
try {
    $dashboardResponse = Invoke-RestMethod -Uri "$baseUrl/admin/dashboard" -Method GET -Headers $headers
    Write-Host "✅ Dashboard admin récupéré" -ForegroundColor Green
    Write-Host "Total utilisateurs: $($dashboardResponse.data.total_users)" -ForegroundColor Gray
    Write-Host "Total contrats: $($dashboardResponse.data.total_contracts)" -ForegroundColor Gray
} catch {
    Write-Host "❌ Erreur dashboard admin: $($_.Exception.Message)" -ForegroundColor Red
}

# Test des utilisateurs
Write-Host "`n3. Test des utilisateurs..." -ForegroundColor Yellow
try {
    $usersResponse = Invoke-RestMethod -Uri "$baseUrl/admin/users" -Method GET -Headers $headers
    Write-Host "✅ Utilisateurs récupérés: $($usersResponse.data.Count) utilisateurs" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur utilisateurs: $($_.Exception.Message)" -ForegroundColor Red
}

# Test des logs système
Write-Host "`n4. Test des logs système..." -ForegroundColor Yellow
try {
    $logsResponse = Invoke-RestMethod -Uri "$baseUrl/admin/system/logs" -Method GET -Headers $headers
    Write-Host "✅ Logs système récupérés: $($logsResponse.data.Count) logs" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur logs système: $($_.Exception.Message)" -ForegroundColor Red
}

# Test des statistiques système
Write-Host "`n5. Test des statistiques système..." -ForegroundColor Yellow
try {
    $statsResponse = Invoke-RestMethod -Uri "$baseUrl/admin/system/stats" -Method GET -Headers $headers
    Write-Host "✅ Statistiques système récupérées" -ForegroundColor Green
    Write-Host "CPU: $($statsResponse.data.cpu_usage)%" -ForegroundColor Gray
    Write-Host "Mémoire: $($statsResponse.data.memory_usage)%" -ForegroundColor Gray
} catch {
    Write-Host "❌ Erreur statistiques système: $($_.Exception.Message)" -ForegroundColor Red
}

# Test des sauvegardes
Write-Host "`n6. Test des sauvegardes..." -ForegroundColor Yellow
try {
    $backupsResponse = Invoke-RestMethod -Uri "$baseUrl/admin/system/backups" -Method GET -Headers $headers
    Write-Host "✅ Sauvegardes récupérées: $($backupsResponse.data.Count) sauvegardes" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur sauvegardes: $($_.Exception.Message)" -ForegroundColor Red
}

# Test de la configuration système
Write-Host "`n7. Test de la configuration système..." -ForegroundColor Yellow
try {
    $configResponse = Invoke-RestMethod -Uri "$baseUrl/admin/system/config" -Method GET -Headers $headers
    Write-Host "✅ Configuration système récupérée" -ForegroundColor Green
    Write-Host "Nom app: $($configResponse.data.app_name)" -ForegroundColor Gray
} catch {
    Write-Host "❌ Erreur configuration système: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=== FIN DES TESTS ===" -ForegroundColor Green
