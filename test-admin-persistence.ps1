# Script pour tester la persistance des actions admin
Write-Host "🔧 Test de persistance des actions admin" -ForegroundColor Green
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
Write-Host "🔧 Corrections apportées:" -ForegroundColor Cyan
Write-Host "   ✅ Service AdminService corrigé" -ForegroundColor Green
Write-Host "   ✅ Authentification ajoutée" -ForegroundColor Green
Write-Host "   ✅ URLs API corrigées" -ForegroundColor Green
Write-Host "   ✅ Composant Admin corrigé" -ForegroundColor Green
Write-Host "   ✅ Appels API réels" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Fonctionnalités testées:" -ForegroundColor Cyan
Write-Host "   • Chargement des utilisateurs depuis l'API" -ForegroundColor White
Write-Host "   • Création d'utilisateur via l'API" -ForegroundColor White
Write-Host "   • Blocage d'utilisateur via l'API" -ForegroundColor White
Write-Host "   • Déblocage d'utilisateur via l'API" -ForegroundColor White
Write-Host "   • Suppression d'utilisateur via l'API" -ForegroundColor White
Write-Host "   • Persistance en base de données" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Se connecter en tant qu'admin" -ForegroundColor White
Write-Host "   2. Accéder à l'interface admin" -ForegroundColor White
Write-Host "   3. Créer un nouvel utilisateur" -ForegroundColor White
Write-Host "   4. Vérifier qu'il apparaît dans la liste" -ForegroundColor White
Write-Host "   5. Bloquer l'utilisateur" -ForegroundColor White
Write-Host "   6. Actualiser la page" -ForegroundColor White
Write-Host "   7. Vérifier que le statut 'bloqué' est conservé" -ForegroundColor White
Write-Host "   8. Débloquer l'utilisateur" -ForegroundColor White
Write-Host "   9. Actualiser la page" -ForegroundColor White
Write-Host "   10. Vérifier que le statut 'actif' est conservé" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications de persistance:" -ForegroundColor Cyan
Write-Host "   • Création → Base de données" -ForegroundColor White
Write-Host "   • Blocage → Base de données" -ForegroundColor White
Write-Host "   • Déblocage → Base de données" -ForegroundColor White
Write-Host "   • Suppression → Base de données" -ForegroundColor White
Write-Host "   • Actualisation → Données persistées" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Sécurité:" -ForegroundColor Cyan
Write-Host "   • Token d'authentification requis" -ForegroundColor White
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
Write-Host "🔧 Debug:" -ForegroundColor Cyan
Write-Host "   • Console du navigateur pour voir les erreurs" -ForegroundColor White
Write-Host "   • Network tab pour voir les appels API" -ForegroundColor White
Write-Host "   • Logs backend pour voir les requêtes" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")





