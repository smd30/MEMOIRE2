# Script pour démarrer les serveurs et tester le module devis
Write-Host "=== Démarrage des Serveurs et Test du Module Devis ===" -ForegroundColor Green
Write-Host ""

# 1. Démarrer le serveur backend Laravel
Write-Host "1. Démarrage du serveur backend Laravel..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd C:\asaaa\backend; php artisan serve --host=0.0.0.0 --port=8000"

# Attendre que le backend démarre
Write-Host "   Attente du démarrage du backend..." -ForegroundColor Cyan
Start-Sleep -Seconds 5

# 2. Créer un utilisateur de test et tester l'API
Write-Host "2. Test de l'authentification et création d'utilisateur..." -ForegroundColor Yellow
cd backend
php test-auth.php
cd ..

Write-Host ""

# 3. Démarrer le serveur frontend Angular
Write-Host "3. Démarrage du serveur frontend Angular..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd C:\asaaa\frontend; ng serve"

Write-Host ""
Write-Host "=== Serveurs Démarrés ===" -ForegroundColor Green
Write-Host ""
Write-Host "🔗 Liens d'accès:" -ForegroundColor Yellow
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor Cyan
Write-Host "   • Backend API: http://localhost:8000/api" -ForegroundColor Cyan
Write-Host ""
Write-Host "👤 Identifiants de test:" -ForegroundColor Yellow
Write-Host "   • Email: client@test.com" -ForegroundColor Cyan
Write-Host "   • Mot de passe: password123" -ForegroundColor Cyan
Write-Host ""
Write-Host "📋 Instructions de test:" -ForegroundColor Yellow
Write-Host "   1. Ouvrez http://localhost:4200" -ForegroundColor White
Write-Host "   2. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "   3. Utilisez les identifiants ci-dessus" -ForegroundColor White
Write-Host "   4. Cliquez sur 'DEVIS' dans le menu" -ForegroundColor White
Write-Host "   5. Cliquez sur 'Nouveau devis'" -ForegroundColor White
Write-Host "   6. Testez le formulaire multi-étapes" -ForegroundColor White
Write-Host ""
Write-Host "✅ Les serveurs sont maintenant prêts !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur Ctrl+C pour arrêter ce script..." -ForegroundColor Red

# Garder le script en vie
while ($true) {
    Start-Sleep -Seconds 10
}
