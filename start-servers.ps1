# Script pour démarrer les serveurs backend et frontend
Write-Host "=== Démarrage des Serveurs ===" -ForegroundColor Green
Write-Host ""

# Démarrer le backend Laravel
Write-Host "1. Démarrage du backend Laravel..." -ForegroundColor Yellow
Write-Host "   Commande: cd backend; php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Cyan
Start-Process powershell -ArgumentList "-Command", "cd backend; php artisan serve --host=0.0.0.0 --port=8000" -WindowStyle Normal
Write-Host "   ✅ Backend démarré sur http://localhost:8000" -ForegroundColor Green

# Attendre un peu
Start-Sleep -Seconds 3

# Démarrer le frontend Angular
Write-Host ""
Write-Host "2. Démarrage du frontend Angular..." -ForegroundColor Yellow
Write-Host "   Commande: cd frontend; npm start" -ForegroundColor Cyan
Start-Process powershell -ArgumentList "-Command", "cd frontend; npm start" -WindowStyle Normal
Write-Host "   ✅ Frontend démarré sur http://localhost:4200" -ForegroundColor Green

Write-Host ""
Write-Host "=== Serveurs Démarrés ===" -ForegroundColor Green
Write-Host ""
Write-Host "🔗 Liens d'accès:" -ForegroundColor Yellow
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor Cyan
Write-Host "   • Inscription: http://localhost:4200/register" -ForegroundColor Cyan
Write-Host "   • Backend API: http://localhost:8000/api" -ForegroundColor Cyan
Write-Host ""
Write-Host "📋 Test d'inscription: test-inscription.html" -ForegroundColor Yellow
Write-Host ""
Write-Host "✅ Les serveurs sont maintenant prêts !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
