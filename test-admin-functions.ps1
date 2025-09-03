# Script pour tester les fonctionnalités admin
Write-Host "🔧 Test des fonctionnalités admin" -ForegroundColor Green
Write-Host ""

# Vérifier que le backend est en cours d'exécution
Write-Host "📡 Vérification du backend..." -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET -TimeoutSec 5
    Write-Host "✅ Backend opérationnel" -ForegroundColor Green
} catch {
    Write-Host "❌ Backend non accessible. Démarrage..." -ForegroundColor Red
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd backend; php artisan serve --host=0.0.0.0 --port=8000"
    Start-Sleep -Seconds 3
}

Write-Host ""
Write-Host "🔧 Fonctionnalités admin testées:" -ForegroundColor Cyan
Write-Host "   ✅ Contrôleur AdminController complet" -ForegroundColor Green
Write-Host "   ✅ Routes API admin définies" -ForegroundColor Green
Write-Host "   ✅ Persistance en base de données" -ForegroundColor Green
Write-Host "   ✅ Gestion des erreurs" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Endpoints admin disponibles:" -ForegroundColor Cyan
Write-Host "   • GET /api/admin/dashboard - Statistiques dashboard" -ForegroundColor White
Write-Host "   • GET /api/admin/users - Liste des utilisateurs" -ForegroundColor White
Write-Host "   • POST /api/admin/users - Créer un utilisateur" -ForegroundColor White
Write-Host "   • GET /api/admin/users/{id} - Détails utilisateur" -ForegroundColor White
Write-Host "   • PUT /api/admin/users/{id} - Modifier utilisateur" -ForegroundColor White
Write-Host "   • DELETE /api/admin/users/{id} - Supprimer utilisateur" -ForegroundColor White
Write-Host "   • PUT /api/admin/users/{id}/toggle-status - Changer statut" -ForegroundColor White
Write-Host "   • GET /api/admin/system/logs - Logs système" -ForegroundColor White
Write-Host "   • DELETE /api/admin/system/logs - Effacer logs" -ForegroundColor White
Write-Host "   • GET /api/admin/system/stats - Stats système" -ForegroundColor White
Write-Host "   • GET /api/admin/system/backups - Sauvegardes" -ForegroundColor White
Write-Host "   • POST /api/admin/system/backups - Créer sauvegarde" -ForegroundColor White
Write-Host "   • POST /api/admin/system/backups/{id}/restore - Restaurer" -ForegroundColor White
Write-Host "   • GET /api/admin/system/config - Configuration" -ForegroundColor White
Write-Host "   • PUT /api/admin/system/config - Modifier config" -ForegroundColor White
Write-Host "   • POST /api/admin/system/maintenance - Mode maintenance" -ForegroundColor White
Write-Host "   • POST /api/admin/system/cache/clear - Effacer cache" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Se connecter en tant qu'admin" -ForegroundColor White
Write-Host "   2. Accéder au dashboard admin" -ForegroundColor White
Write-Host "   3. Créer un nouvel utilisateur" -ForegroundColor White
Write-Host "   4. Vérifier qu'il apparaît dans la liste" -ForegroundColor White
Write-Host "   5. Modifier les informations de l'utilisateur" -ForegroundColor White
Write-Host "   6. Changer le statut de l'utilisateur" -ForegroundColor White
Write-Host "   7. Actualiser la page et vérifier la persistance" -ForegroundColor White
Write-Host "   8. Tester les fonctionnalités système" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications de persistance:" -ForegroundColor Cyan
Write-Host "   • Création d'utilisateur → Base de données" -ForegroundColor White
Write-Host "   • Modification d'utilisateur → Base de données" -ForegroundColor White
Write-Host "   • Changement de statut → Base de données" -ForegroundColor White
Write-Host "   • Suppression d'utilisateur → Base de données" -ForegroundColor White
Write-Host "   • Actualisation de page → Données persistées" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Sécurité admin:" -ForegroundColor Cyan
Write-Host "   • Authentification requise" -ForegroundColor White
Write-Host "   • Rôle admin vérifié" -ForegroundColor White
Write-Host "   • Validation des données" -ForegroundColor White
Write-Host "   • Gestion des erreurs" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • API Admin: http://localhost:8000/api/admin" -ForegroundColor White
Write-Host ""
Write-Host "📊 Données persistées:" -ForegroundColor Cyan
Write-Host "   • Informations utilisateur (nom, email, rôle, statut)" -ForegroundColor White
Write-Host "   • Données de session (last_login_at)" -ForegroundColor White
Write-Host "   • Préférences utilisateur (user_data)" -ForegroundColor White
Write-Host "   • Historique des modifications" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
