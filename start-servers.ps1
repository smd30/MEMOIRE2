# Script pour démarrer les serveurs backend et frontend
Write-Host "=== DÉMARRAGE DES SERVEURS ===" -ForegroundColor Green

# Démarrer le serveur backend Laravel
Write-Host "`n1. Démarrage du serveur backend Laravel..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd backend; php artisan serve" -WindowStyle Normal

# Attendre un peu pour que le backend démarre
Write-Host "Attente du démarrage du backend..." -ForegroundColor Gray
Start-Sleep -Seconds 3

# Démarrer le serveur frontend Angular
Write-Host "`n2. Démarrage du serveur frontend Angular..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd frontend; ng serve" -WindowStyle Normal

Write-Host "`n=== SERVEURS DÉMARRÉS ===" -ForegroundColor Green
Write-Host "Backend: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Frontend: http://localhost:4200" -ForegroundColor Cyan

Write-Host "`n=== INSTRUCTIONS DE CONNEXION ===" -ForegroundColor Yellow
Write-Host "1. Ouvrez votre navigateur sur http://localhost:4200" -ForegroundColor White
Write-Host "2. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "3. Utilisez les identifiants admin:" -ForegroundColor White
Write-Host "   Email: admin@example.com" -ForegroundColor Cyan
Write-Host "   Mot de passe: password" -ForegroundColor Cyan
Write-Host "4. Vous serez automatiquement redirigé vers l'interface admin" -ForegroundColor White

Write-Host "`n=== AUTRES UTILISATEURS DISPONIBLES ===" -ForegroundColor Yellow
Write-Host "Gestionnaire: gestionnaire@example.com / password" -ForegroundColor Cyan
Write-Host "Client: client@example.com / password" -ForegroundColor Cyan
