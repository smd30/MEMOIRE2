Write-Host "Diagnostic du deploiement Render..." -ForegroundColor Green

Write-Host "Verification de la configuration:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Dockerfile Path: ./backend/Dockerfile" -ForegroundColor Cyan
Write-Host "2. Docker Context: ./backend" -ForegroundColor Cyan
Write-Host "3. Plan: Free" -ForegroundColor Cyan
Write-Host "4. Region: Oregon" -ForegroundColor Cyan
Write-Host "5. Branch: main" -ForegroundColor Cyan
Write-Host ""
Write-Host "Variables d'environnement REQUISES:" -ForegroundColor Yellow
Write-Host "APP_ENV=production" -ForegroundColor White
Write-Host "APP_DEBUG=false" -ForegroundColor White
Write-Host "APP_NAME=MEMOIRE2" -ForegroundColor White
Write-Host "APP_URL=https://votre-service.onrender.com" -ForegroundColor White
Write-Host ""
Write-Host "Base de donnees REQUISE:" -ForegroundColor Yellow
Write-Host "- Type: PostgreSQL" -ForegroundColor White
Write-Host "- Plan: Free" -ForegroundColor White
Write-Host "- Nom: memoire2-db" -ForegroundColor White
Write-Host ""
Write-Host "Si le deploiement echoue:" -ForegroundColor Red
Write-Host "1. Verifiez les logs Render" -ForegroundColor White
Write-Host "2. Verifiez la configuration ci-dessus" -ForegroundColor White
Write-Host "3. Supprimez et recreez le service" -ForegroundColor White
Write-Host "4. Partagez les logs d'erreur" -ForegroundColor White
