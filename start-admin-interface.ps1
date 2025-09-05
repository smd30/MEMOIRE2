# Script pour démarrer l'interface administrateur
Write-Host "🚀 Démarrage de l'interface administrateur..." -ForegroundColor Green

# Démarrer le serveur backend Laravel
Write-Host "📡 Démarrage du serveur backend..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd backend; php artisan serve --host=0.0.0.0 --port=8000"

# Attendre un peu pour que le backend démarre
Start-Sleep -Seconds 3

# Démarrer le serveur frontend Angular
Write-Host "🌐 Démarrage du serveur frontend..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd frontend; ng serve --port=4200"

# Attendre un peu pour que le frontend démarre
Start-Sleep -Seconds 5

Write-Host "✅ Interface administrateur démarrée avec succès!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Accès aux interfaces:" -ForegroundColor Cyan
Write-Host "   • Frontend (Angular): http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend (Laravel): http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "🔐 Rôles disponibles:" -ForegroundColor Cyan
Write-Host "   • Client: Accès aux fonctionnalités client (devis, contrats, sinistres)" -ForegroundColor White
Write-Host "   • Gestionnaire: Accès aux fonctionnalités de gestion" -ForegroundColor White
Write-Host "   • Admin: Accès complet à l'administration" -ForegroundColor White
Write-Host ""
Write-Host "👤 Test de l'interface administrateur:" -ForegroundColor Cyan
Write-Host "   1. Créez un compte avec le rôle 'admin'" -ForegroundColor White
Write-Host "   2. Connectez-vous avec ce compte" -ForegroundColor White
Write-Host "   3. Vous serez automatiquement redirigé vers /admin" -ForegroundColor White
Write-Host "   4. Testez les fonctionnalités: ajouter, bloquer, débloquer des utilisateurs" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Fonctionnalités administrateur:" -ForegroundColor Cyan
Write-Host "   • Consulter la liste des utilisateurs" -ForegroundColor White
Write-Host "   • Ajouter de nouveaux utilisateurs" -ForegroundColor White
Write-Host "   • Bloquer/débloquer des utilisateurs" -ForegroundColor White
Write-Host "   • Supprimer des utilisateurs" -ForegroundColor White
Write-Host "   • Recherche et filtrage" -ForegroundColor White
Write-Host ""
Write-Host "⚠️  Note: Les administrateurs ne peuvent pas être bloqués ou supprimés" -ForegroundColor Yellow
Write-Host ""
Write-Host "🔄 Redirection automatique selon le rôle:" -ForegroundColor Cyan
Write-Host "   • Client → /dashboard (Mes Véhicules)" -ForegroundColor White
Write-Host "   • Gestionnaire → /gestionnaire" -ForegroundColor White
Write-Host "   • Admin → /admin" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")



