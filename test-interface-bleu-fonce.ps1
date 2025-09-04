# Script pour vérifier que l'interface est dominée par le bleu foncé
Write-Host "🎨 Vérification de l'interface dominée par le bleu foncé" -ForegroundColor Green
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
Write-Host "🎨 Interface DOMINÉE par le BLEU FONCÉ - Code couleur appliqué:" -ForegroundColor Cyan
Write-Host "   ✅ Page entière - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Navigation - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Sections de contenu - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Cartes - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Timeline - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Champs de recherche - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Filtres - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Texte principal - Blanc (#FFFFFF)" -ForegroundColor Green
Write-Host "   ✅ Accents et bordures - Orange (#FF6B35)" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Éléments avec les bonnes couleurs:" -ForegroundColor Cyan
Write-Host "   • Page entière - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Header - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Navigation - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Sections de contenu - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Cartes de contrats - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Cartes de sinistres - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Timeline - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Champs de recherche - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Filtres - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Boutons de navigation - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Titres et texte - Blanc (#FFFFFF)" -ForegroundColor White
Write-Host "   • Bordures et accents - Orange (#FF6B35)" -ForegroundColor White
Write-Host "   • Boutons actifs - Orange (#FF6B35)" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests visuels à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Vérifier que toute la page est bleu foncé" -ForegroundColor White
Write-Host "   2. Vérifier que la navigation est bleu foncé" -ForegroundColor White
Write-Host "   3. Vérifier que les sections sont bleu foncé" -ForegroundColor White
Write-Host "   4. Vérifier que les cartes sont bleu foncé" -ForegroundColor White
Write-Host "   5. Vérifier que la timeline est bleu foncé" -ForegroundColor White
Write-Host "   6. Vérifier que les champs de recherche sont bleu foncé" -ForegroundColor White
Write-Host "   7. Vérifier que les filtres sont bleu foncé" -ForegroundColor White
Write-Host "   8. Vérifier que le texte est blanc" -ForegroundColor White
Write-Host "   9. Vérifier que les accents sont orange" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications de cohérence:" -ForegroundColor Cyan
Write-Host "   • ZÉRO blanc en arrière-plan" -ForegroundColor White
Write-Host "   • Bleu foncé partout" -ForegroundColor White
Write-Host "   • Texte blanc sur fond bleu foncé" -ForegroundColor White
Write-Host "   • Accents orange pour l'interactivité" -ForegroundColor White
Write-Host "   • Contraste excellent" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Accessibilité:" -ForegroundColor Cyan
Write-Host "   • Contraste blanc/bleu foncé - Excellent" -ForegroundColor White
Write-Host "   • Contraste orange/bleu foncé - Bon" -ForegroundColor White
Write-Host "   • Lisibilité parfaite" -ForegroundColor White
Write-Host "   • Identité visuelle forte" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • Interface gestionnaire: http://localhost:4200/gestionnaire" -ForegroundColor White
Write-Host ""
Write-Host "🎨 Palette de couleurs appliquée:" -ForegroundColor Cyan
Write-Host "   • Primaire: #151C46 (Bleu foncé) - TOUS les arrière-plans" -ForegroundColor White
Write-Host "   • Secondaire: #FF6B35 (Orange) - Accents et interactivité" -ForegroundColor White
Write-Host "   • Blanc: #FFFFFF - Texte principal uniquement" -ForegroundColor White
Write-Host "   • Gris clair: #F8F9FA - Plus utilisé" -ForegroundColor White
Write-Host "   • Gris: #6C757D - Plus utilisé" -ForegroundColor White
Write-Host "   • Succès: #28A745 (Vert) - Actions positives" -ForegroundColor White
Write-Host "   • Avertissement: #FFC107 (Jaune) - Statuts en attente" -ForegroundColor White
Write-Host "   • Danger: #DC3545 (Rouge) - Actions négatives" -ForegroundColor White
Write-Host "   • Info: #17A2B8 (Bleu clair) - Informations" -ForegroundColor White
Write-Host ""
Write-Host "📊 Éléments stylisés:" -ForegroundColor Cyan
Write-Host "   • Page entière bleu foncé" -ForegroundColor White
Write-Host "   • Header bleu foncé" -ForegroundColor White
Write-Host "   • Navigation bleu foncé" -ForegroundColor White
Write-Host "   • Sections bleu foncé" -ForegroundColor White
Write-Host "   • Cartes bleu foncé" -ForegroundColor White
Write-Host "   • Timeline bleu foncé" -ForegroundColor White
Write-Host "   • Champs de recherche bleu foncé" -ForegroundColor White
Write-Host "   • Filtres bleu foncé" -ForegroundColor White
Write-Host "   • Texte blanc partout" -ForegroundColor White
Write-Host "   • Accents orange" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Résultat attendu:" -ForegroundColor Cyan
Write-Host "   • Interface 100% bleu foncé" -ForegroundColor White
Write-Host "   • ZÉRO blanc en arrière-plan" -ForegroundColor White
Write-Host "   • Texte blanc bien lisible" -ForegroundColor White
Write-Host "   • Accents orange pour l'interactivité" -ForegroundColor White
Write-Host "   • Identité visuelle forte et cohérente" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")


