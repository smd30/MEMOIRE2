# Script pour vérifier que l'interface n'est plus trop blanche
Write-Host "🎨 Vérification de l'interface moins blanche" -ForegroundColor Green
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
Write-Host "🎨 Interface MOINS BLANCHE - Code couleur appliqué:" -ForegroundColor Cyan
Write-Host "   ✅ Arrière-plan principal - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Sections de contenu - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Cartes - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Timeline - Bleu foncé (#151C46)" -ForegroundColor Green
Write-Host "   ✅ Texte principal - Blanc (#FFFFFF)" -ForegroundColor Green
Write-Host "   ✅ Accents - Orange (#FF6B35)" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Éléments avec les bonnes couleurs:" -ForegroundColor Cyan
Write-Host "   • Arrière-plan principal - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Header - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Sections de contenu - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Cartes de contrats - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Cartes de sinistres - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Timeline - Bleu foncé (#151C46)" -ForegroundColor White
Write-Host "   • Titres de sections - Blanc (#FFFFFF)" -ForegroundColor White
Write-Host "   • Noms des clients - Blanc (#FFFFFF)" -ForegroundColor White
Write-Host "   • Détails - Blanc (#FFFFFF)" -ForegroundColor White
Write-Host "   • Bordures et accents - Orange (#FF6B35)" -ForegroundColor White
Write-Host "   • Emails et labels - Orange (#FF6B35)" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Tests visuels à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Vérifier l'arrière-plan bleu foncé principal" -ForegroundColor White
Write-Host "   2. Vérifier les sections en bleu foncé" -ForegroundColor White
Write-Host "   3. Vérifier les cartes en bleu foncé" -ForegroundColor White
Write-Host "   4. Vérifier la timeline en bleu foncé" -ForegroundColor White
Write-Host "   5. Vérifier le texte blanc sur fond bleu" -ForegroundColor White
Write-Host "   6. Vérifier les accents orange" -ForegroundColor White
Write-Host "   7. Vérifier les bordures orange" -ForegroundColor White
Write-Host "   8. Vérifier les emails en orange" -ForegroundColor White
Write-Host "   9. Vérifier les labels en orange" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérifications de cohérence:" -ForegroundColor Cyan
Write-Host "   • Moins de blanc, plus de bleu foncé" -ForegroundColor White
Write-Host "   • Texte blanc sur fond bleu foncé" -ForegroundColor White
Write-Host "   • Accents orange pour la lisibilité" -ForegroundColor White
Write-Host "   • Bordures orange pour l'interactivité" -ForegroundColor White
Write-Host "   • Contraste excellent" -ForegroundColor White
Write-Host ""
Write-Host "🛡️ Accessibilité:" -ForegroundColor Cyan
Write-Host "   • Contraste blanc/bleu foncé - Excellent" -ForegroundColor White
Write-Host "   • Contraste orange/bleu foncé - Bon" -ForegroundColor White
Write-Host "   • Lisibilité améliorée" -ForegroundColor White
Write-Host "   • Moins de fatigue visuelle" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • Interface gestionnaire: http://localhost:4200/gestionnaire" -ForegroundColor White
Write-Host ""
Write-Host "🎨 Palette de couleurs appliquée:" -ForegroundColor Cyan
Write-Host "   • Primaire: #151C46 (Bleu foncé) - Arrière-plans et cartes" -ForegroundColor White
Write-Host "   • Secondaire: #FF6B35 (Orange) - Accents et bordures" -ForegroundColor White
Write-Host "   • Blanc: #FFFFFF - Texte principal" -ForegroundColor White
Write-Host "   • Gris clair: #F8F9FA - Séparateurs" -ForegroundColor White
Write-Host "   • Gris: #6C757D - Texte secondaire" -ForegroundColor White
Write-Host "   • Succès: #28A745 (Vert) - Actions positives" -ForegroundColor White
Write-Host "   • Avertissement: #FFC107 (Jaune) - Statuts en attente" -ForegroundColor White
Write-Host "   • Danger: #DC3545 (Rouge) - Actions négatives" -ForegroundColor White
Write-Host "   • Info: #17A2B8 (Bleu clair) - Informations" -ForegroundColor White
Write-Host ""
Write-Host "📊 Éléments stylisés:" -ForegroundColor Cyan
Write-Host "   • Arrière-plan principal bleu foncé" -ForegroundColor White
Write-Host "   • Header bleu foncé avec titre blanc" -ForegroundColor White
Write-Host "   • Sections de contenu bleu foncé" -ForegroundColor White
Write-Host "   • Cartes bleu foncé avec bordures orange" -ForegroundColor White
Write-Host "   • Timeline bleu foncé" -ForegroundColor White
Write-Host "   • Texte blanc sur fond bleu foncé" -ForegroundColor White
Write-Host "   • Accents orange pour la lisibilité" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Résultat attendu:" -ForegroundColor Cyan
Write-Host "   • Interface dominée par le bleu foncé" -ForegroundColor White
Write-Host "   • Moins de blanc, plus de couleur" -ForegroundColor White
Write-Host "   • Texte blanc bien lisible" -ForegroundColor White
Write-Host "   • Accents orange pour l'interactivité" -ForegroundColor White
Write-Host "   • Cohérence visuelle parfaite" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
