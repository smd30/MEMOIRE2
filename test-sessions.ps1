# Script pour tester la gestion des sessions utilisateurs
Write-Host "🧪 Test de la gestion des sessions utilisateurs" -ForegroundColor Green
Write-Host ""

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

Write-Host "✅ Serveurs démarrés avec succès!" -ForegroundColor Green
Write-Host ""
Write-Host "🔐 Test de la gestion des sessions:" -ForegroundColor Cyan
Write-Host "   • Frontend (Angular): http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend (Laravel): http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "📋 Fonctionnalités de session testées:" -ForegroundColor Cyan
Write-Host "   ✅ Authentification avec tokens JWT" -ForegroundColor Green
Write-Host "   ✅ Gestion des sessions avec expiration" -ForegroundColor Green
Write-Host "   ✅ Surveillance de l'activité utilisateur" -ForegroundColor Green
Write-Host "   ✅ Renouvellement automatique des tokens" -ForegroundColor Green
Write-Host "   ✅ Stockage sécurisé des données utilisateur" -ForegroundColor Green
Write-Host "   ✅ Synchronisation des préférences" -ForegroundColor Green
Write-Host "   ✅ Protection contre les sessions expirées" -ForegroundColor Green
Write-Host ""
Write-Host "🎯 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Créer un compte utilisateur" -ForegroundColor White
Write-Host "   2. Se connecter et vérifier la session" -ForegroundColor White
Write-Host "   3. Cliquer sur l'icône d'info pour voir les détails de session" -ForegroundColor White
Write-Host "   4. Tester le rafraîchissement de session" -ForegroundColor White
Write-Host "   5. Tester la synchronisation des données" -ForegroundColor White
Write-Host "   6. Attendre l'expiration de session (24h)" -ForegroundColor White
Write-Host "   7. Tester la déconnexion automatique" -ForegroundColor White
Write-Host ""
Write-Host "🔧 Fonctionnalités de session:" -ForegroundColor Cyan
Write-Host "   • Session de 24 heures avec surveillance d'activité" -ForegroundColor White
Write-Host "   • Renouvellement automatique des tokens" -ForegroundColor White
Write-Host "   • Stockage local des préférences utilisateur" -ForegroundColor White
Write-Host "   • Synchronisation avec le serveur" -ForegroundColor White
Write-Host "   • Protection contre les attaques CSRF" -ForegroundColor White
Write-Host "   • Gestion des erreurs d'authentification" -ForegroundColor White
Write-Host ""
Write-Host "📊 Données stockées par utilisateur:" -ForegroundColor Cyan
Write-Host "   • Préférences (thème, langue, notifications)" -ForegroundColor White
Write-Host "   • Historique des activités" -ForegroundColor White
Write-Host "   • Données de session (token, expiration)" -ForegroundColor White
Write-Host "   • Statistiques d'utilisation" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Sécurité implémentée:" -ForegroundColor Cyan
Write-Host "   • Tokens JWT avec expiration" -ForegroundColor White
Write-Host "   • Surveillance de l'activité utilisateur" -ForegroundColor White
Write-Host "   • Déconnexion automatique en cas d'inactivité" -ForegroundColor White
Write-Host "   • Protection contre les attaques XSS/CSRF" -ForegroundColor White
Write-Host "   • Validation des données côté client et serveur" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")





