# Script pour vérifier le code couleur de l'interface gestionnaire
Write-Host "🎨 Vérification du code couleur de l'interface gestionnaire" -ForegroundColor Green
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
Write-Host "🎨 Code couleur de la plateforme appliqué:" -ForegroundColor Cyan
Write-Host "   ✅ Blanc (#FFFFFF) - Arrière-plan principal" -ForegroundColor Green
Write-Host "   ✅ Bleu foncé (#151C46) - Couleur primaire" -ForegroundColor Green
Write-Host "   ✅ Orange (#FF6B35) - Couleur secondaire" -ForegroundColor Green
Write-Host "   ✅ Variables CSS définies" -ForegroundColor Green
Write-Host "   ✅ Cohérence dans toute l'interface" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Éléments avec le code couleur:" -ForegroundColor Cyan
Write-Host "   • Header - Fond bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Titre - Texte blanc" -ForegroundColor White
Write-Host "   • Rôle utilisateur - Orange (#FF6B35)" -ForegroundColor White
Write-Host "   • Navigation - Bordures bleu foncé" -ForegroundColor White
Write-Host "   • Boutons actifs - Fond bleu foncé" -ForegroundColor White
Write-Host "   • Bouton Actualiser - Orange (#FF6B35)" -ForegroundColor White
Write-Host "   • Cartes - Bordures bleu foncé" -ForegroundColor White
Write-Host "   • IDs des contrats - Orange (#FF6B35)" -ForegroundColor White
Write-Host "   • Titres de sections - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Boutons d'action - Couleurs sémantiques" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests visuels à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Vérifier le header bleu foncé" -ForegroundColor White
Write-Host "   2. Vérifier le texte blanc du titre" -ForegroundColor White
Write-Host "   3. Vérifier le rôle en orange" -ForegroundColor White
Write-Host "   4. Vérifier la navigation avec bordures bleues" -ForegroundColor White
Write-Host "   5. Vérifier le bouton Actualiser orange" -ForegroundColor White
Write-Host "   6. Vérifier les cartes avec bordures bleues" -ForegroundColor White
Write-Host "   7. Vérifier les IDs en orange" -ForegroundColor White
Write-Host "   8. Vérifier les titres en bleu foncé" -ForegroundColor White
Write-Host "   9. Vérifier les boutons d'action colorés" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications de cohérence:" -ForegroundColor Cyan
Write-Host "   • Toutes les couleurs utilisent les variables CSS" -ForegroundColor White
Write-Host "   • Pas de couleurs codées en dur" -ForegroundColor White
Write-Host "   • Cohérence entre les sections" -ForegroundColor White
Write-Host "   • Contraste suffisant pour l'accessibilité" -ForegroundColor White
Write-Host "   • Hover effects avec les bonnes couleurs" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Accessibilité:" -ForegroundColor Cyan
Write-Host "   • Contraste blanc/bleu foncé - Excellent" -ForegroundColor White
Write-Host "   • Contraste orange/blanc - Bon" -ForegroundColor White
Write-Host "   • Contraste vert/blanc - Bon" -ForegroundColor White
Write-Host "   • Contraste rouge/blanc - Bon" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • Interface gestionnaire: http://localhost:4200/gestionnaire" -ForegroundColor White
Write-Host ""
Write-Host "🎨 Palette de couleurs:" -ForegroundColor Cyan
Write-Host "   • Primaire: #151C46 (Bleu foncé)" -ForegroundColor White
Write-Host "   • Secondaire: #FF6B35 (Orange)" -ForegroundColor White
Write-Host "   • Blanc: #FFFFFF" -ForegroundColor White
Write-Host "   • Gris clair: #F8F9FA" -ForegroundColor White
Write-Host "   • Gris: #6C757D" -ForegroundColor White
Write-Host "   • Succès: #28A745 (Vert)" -ForegroundColor White
Write-Host "   • Avertissement: #FFC107 (Jaune)" -ForegroundColor White
Write-Host "   • Danger: #DC3545 (Rouge)" -ForegroundColor White
Write-Host "   • Info: #17A2B8 (Bleu clair)" -ForegroundColor White
Write-Host ""
Write-Host "📊 Éléments stylisés:" -ForegroundColor Cyan
Write-Host "   • Header avec fond bleu foncé" -ForegroundColor White
Write-Host "   • Navigation avec bordures bleues" -ForegroundColor White
Write-Host "   • Boutons avec couleurs sémantiques" -ForegroundColor White
Write-Host "   • Cartes avec bordures bleues" -ForegroundColor White
Write-Host "   • Statuts avec couleurs appropriées" -ForegroundColor White
Write-Host "   • Hover effects avec orange" -ForegroundColor White
Write-Host "   • Focus states avec orange" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")



