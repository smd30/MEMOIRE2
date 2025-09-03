# Démarrage rapide pour tester l'interface admin
Write-Host "=== DÉMARRAGE RAPIDE ADMIN ===" -ForegroundColor Green

# Démarrer le serveur backend
Write-Host "`n1. Démarrage du serveur backend Laravel..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd backend; php artisan serve" -WindowStyle Normal

# Attendre que le backend démarre
Write-Host "Attente du démarrage du backend..." -ForegroundColor Gray
Start-Sleep -Seconds 5

# Démarrer le serveur frontend
Write-Host "`n2. Démarrage du serveur frontend Angular..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd frontend; ng serve" -WindowStyle Normal

# Attendre que le frontend démarre
Write-Host "Attente du démarrage du frontend..." -ForegroundColor Gray
Start-Sleep -Seconds 10

# Ouvrir la page de connexion admin
Write-Host "`n3. Ouverture de la page de connexion admin..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/src/app/pages/clear-auth.html"

Write-Host "`n=== INSTRUCTIONS ===" -ForegroundColor Yellow
Write-Host "1. La page de connexion admin s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Les identifiants sont pré-remplis: admin@example.com / password" -ForegroundColor White
Write-Host "3. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "4. Vous serez redirigé vers l'interface admin complète" -ForegroundColor White

Write-Host "`n=== URLS ===" -ForegroundColor Cyan
Write-Host "Backend: http://localhost:8000" -ForegroundColor White
Write-Host "Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "Connexion Admin: http://localhost:4200/src/app/pages/clear-auth.html" -ForegroundColor White

Write-Host "`n🎉 Prêt à tester l'interface admin ! 🎉" -ForegroundColor Green

