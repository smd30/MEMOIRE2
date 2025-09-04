# Script pour démarrer les serveurs backend et frontend
Write-Host "Démarrage des serveurs..." -ForegroundColor Green

# Démarrer le serveur backend Laravel
Write-Host "Démarrage du serveur backend Laravel..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd C:\asaaa\backend; php artisan serve --host=0.0.0.0 --port=8000"

# Attendre un peu pour que le backend démarre
Start-Sleep -Seconds 3

# Démarrer le serveur frontend Angular
Write-Host "Démarrage du serveur frontend Angular..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd C:\asaaa\frontend; ng serve"

Write-Host "Les serveurs sont en cours de démarrage..." -ForegroundColor Green
Write-Host "Backend: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Frontend: http://localhost:4200" -ForegroundColor Cyan
Write-Host "Appuyez sur Ctrl+C pour arrêter ce script" -ForegroundColor Red

# Garder le script en vie
while ($true) {
    Start-Sleep -Seconds 10
}
