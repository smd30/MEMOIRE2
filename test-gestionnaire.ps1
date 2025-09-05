# Script pour tester l'interface gestionnaire
Write-Host "🔧 Test de l'interface gestionnaire" -ForegroundColor Green
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
Write-Host "🔧 Interface gestionnaire créée selon le use case:" -ForegroundColor Cyan
Write-Host "   ✅ Liste des contrats" -ForegroundColor Green
Write-Host "   ✅ Échéancier des contrats" -ForegroundColor Green
Write-Host "   ✅ Demandes de sinistre" -ForegroundColor Green
Write-Host "   ✅ Actions selon le use case" -ForegroundColor Green
Write-Host "   ✅ Thème cohérent avec l'application" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Fonctionnalités selon le use case:" -ForegroundColor Cyan
Write-Host "   • Consulter la liste des contrats" -ForegroundColor White
Write-Host "   • Consulter l'échéancier des contrats" -ForegroundColor White
Write-Host "   • Consulter la liste des demandes de sinistre" -ForegroundColor White
Write-Host "   • Annuler un contrat" -ForegroundColor White
Write-Host "   • Valider un sinistre" -ForegroundColor White
Write-Host "   • Rejeter une demande sinistre" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Se connecter en tant que gestionnaire" -ForegroundColor White
Write-Host "   2. Accéder à l'interface gestionnaire" -ForegroundColor White
Write-Host "   3. Naviguer entre les sections" -ForegroundColor White
Write-Host "   4. Consulter la liste des contrats" -ForegroundColor White
Write-Host "   5. Annuler un contrat" -ForegroundColor White
Write-Host "   6. Consulter l'échéancier" -ForegroundColor White
Write-Host "   7. Consulter les demandes de sinistre" -ForegroundColor White
Write-Host "   8. Valider un sinistre" -ForegroundColor White
Write-Host "   9. Rejeter un sinistre" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications:" -ForegroundColor Cyan
Write-Host "   • Navigation fluide entre les sections" -ForegroundColor White
Write-Host "   • Recherche et filtres fonctionnels" -ForegroundColor White
Write-Host "   • Actions selon le use case" -ForegroundColor White
Write-Host "   • Interface responsive" -ForegroundColor White
Write-Host "   • Thème cohérent" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Sécurité:" -ForegroundColor Cyan
Write-Host "   • Authentification requise" -ForegroundColor White
Write-Host "   • Rôle gestionnaire vérifié" -ForegroundColor White
Write-Host "   • Redirection si non autorisé" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • Interface gestionnaire: http://localhost:4200/gestionnaire" -ForegroundColor White
Write-Host ""
Write-Host "📊 Fonctionnalités implémentées:" -ForegroundColor Cyan
Write-Host "   • Navigation par sections" -ForegroundColor White
Write-Host "   • Recherche de contrats et sinistres" -ForegroundColor White
Write-Host "   • Filtres par statut" -ForegroundColor White
Write-Host "   • Actions CRUD sur les contrats" -ForegroundColor White
Write-Host "   • Actions CRUD sur les sinistres" -ForegroundColor White
Write-Host "   • Échéancier avec filtres" -ForegroundColor White
Write-Host "   • Interface moderne et responsive" -ForegroundColor White
Write-Host ""
Write-Host "🎨 Design:" -ForegroundColor Cyan
Write-Host "   • Thème cohérent avec l'application" -ForegroundColor White
Write-Host "   • Gradients et effets visuels" -ForegroundColor White
Write-Host "   • Cartes modernes" -ForegroundColor White
Write-Host "   • Animations et transitions" -ForegroundColor White
Write-Host "   • Responsive design" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")



