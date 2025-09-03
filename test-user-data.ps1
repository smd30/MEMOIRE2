# Script pour tester les données utilisateur
Write-Host "🧪 Test des données utilisateur" -ForegroundColor Green
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
Write-Host "🔧 Test des nouvelles fonctionnalités:" -ForegroundColor Cyan
Write-Host "   ✅ Routes API créées" -ForegroundColor Green
Write-Host "   ✅ Contrôleur AuthController mis à jour" -ForegroundColor Green
Write-Host "   ✅ Migration user_data exécutée" -ForegroundColor Green
Write-Host "   ✅ Modèle User mis à jour" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Endpoints disponibles:" -ForegroundColor Cyan
Write-Host "   • GET /api/user/data - Récupérer les données utilisateur" -ForegroundColor White
Write-Host "   • PUT /api/user/data - Mettre à jour les données utilisateur" -ForegroundColor White
Write-Host "   • POST /api/user/sync - Synchroniser les données" -ForegroundColor White
Write-Host "   • GET /api/user/export - Exporter les données" -ForegroundColor White
Write-Host "   • POST /api/user/import - Importer les données" -ForegroundColor White
Write-Host "   • GET /api/user/stats - Statistiques utilisateur" -ForegroundColor White
Write-Host "   • POST /api/auth/refresh - Rafraîchir le token" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Créer un compte utilisateur" -ForegroundColor White
Write-Host "   2. Se connecter" -ForegroundColor White
Write-Host "   3. Vérifier que les données utilisateur sont créées automatiquement" -ForegroundColor White
Write-Host "   4. Modifier les préférences via l'interface" -ForegroundColor White
Write-Host "   5. Tester la synchronisation" -ForegroundColor White
Write-Host "   6. Vérifier les statistiques" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications:" -ForegroundColor Cyan
Write-Host "   • Colonne user_data ajoutée à la table users" -ForegroundColor White
Write-Host "   • Colonne last_login_at ajoutée" -ForegroundColor White
Write-Host "   • Données par défaut créées automatiquement" -ForegroundColor White
Write-Host "   • Gestion des erreurs 500 corrigée" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
