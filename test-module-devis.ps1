# Script pour tester le module devis
Write-Host "🚗 Test du Module Devis - Assurance Automobile" -ForegroundColor Green
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
Write-Host "🎯 Module Devis - Fonctionnalités implémentées:" -ForegroundColor Cyan
Write-Host "   ✅ Modèles Laravel (Devis, Compagnie, Garantie, Vehicule)" -ForegroundColor Green
Write-Host "   ✅ Migrations de base de données" -ForegroundColor Green
Write-Host "   ✅ Contrôleur DevisController" -ForegroundColor Green
Write-Host "   ✅ Routes API pour devis" -ForegroundColor Green
Write-Host "   ✅ Service DevisService (Angular)" -ForegroundColor Green
Write-Host "   ✅ Composant DevisFormComponent" -ForegroundColor Green
Write-Host "   ✅ Formulaire multi-étapes" -ForegroundColor Green
Write-Host "   ✅ Simulation de devis" -ForegroundColor Green
Write-Host "   ✅ Upload carte grise" -ForegroundColor Green
Write-Host "   ✅ Calcul automatique des tarifs" -ForegroundColor Green
Write-Host ""

Write-Host "🏢 Compagnies d'assurance simulées:" -ForegroundColor Cyan
Write-Host "   • AssurAuto Sénégal" -ForegroundColor White
Write-Host "   • SécuriVie Assurance" -ForegroundColor White
Write-Host ""

Write-Host "🛡️ Garanties disponibles:" -ForegroundColor Cyan
Write-Host "   • Responsabilité Civile (Obligatoire)" -ForegroundColor White
Write-Host "   • Défense et recours" -ForegroundColor White
Write-Host "   • Bris de glace" -ForegroundColor White
Write-Host "   • Incendie & vol" -ForegroundColor White
Write-Host "   • Dommages tous accidents" -ForegroundColor White
Write-Host "   • Assistance dépannage 24h/24" -ForegroundColor White
Write-Host "   • Protection du conducteur et passagers" -ForegroundColor White
Write-Host "   • Catastrophes naturelles" -ForegroundColor White
Write-Host "   • Dommages collision" -ForegroundColor White
Write-Host "   • Assurance juridique automobile" -ForegroundColor White
Write-Host ""

Write-Host "🚗 Catégories de véhicules:" -ForegroundColor Cyan
Write-Host "   • Voiture particulière" -ForegroundColor White
Write-Host "   • Utilitaire léger" -ForegroundColor White
Write-Host "   • Transport en commun" -ForegroundColor White
Write-Host "   • Poids lourd" -ForegroundColor White
Write-Host "   • Motocycle" -ForegroundColor White
Write-Host "   • Véhicule spécial" -ForegroundColor White
Write-Host "   • Véhicule administratif" -ForegroundColor White
Write-Host ""

Write-Host "📅 Périodes de police:" -ForegroundColor Cyan
Write-Host "   • 1 mois" -ForegroundColor White
Write-Host "   • 3 mois" -ForegroundColor White
Write-Host "   • 6 mois" -ForegroundColor White
Write-Host "   • 12 mois" -ForegroundColor White
Write-Host ""

Write-Host "⚡ Types d'énergie:" -ForegroundColor Cyan
Write-Host "   • Essence" -ForegroundColor White
Write-Host "   • Diesel" -ForegroundColor White
Write-Host "   • Gaz" -ForegroundColor White
Write-Host "   • Électricité" -ForegroundColor White
Write-Host ""

Write-Host "🎯 Processus de devis:" -ForegroundColor Cyan
Write-Host "   1. Informations véhicule + propriétaire" -ForegroundColor White
Write-Host "   2. Choix compagnie et période" -ForegroundColor White
Write-Host "   3. Sélection garanties" -ForegroundColor White
Write-Host "   4. Simulation et validation" -ForegroundColor White
Write-Host ""

Write-Host "💰 Tarification:" -ForegroundColor Cyan
Write-Host "   • RC: Montant fixe selon puissance fiscale" -ForegroundColor White
Write-Host "   • Garanties optionnelles: Pourcentage ou forfait" -ForegroundColor White
Write-Host "   • Remises selon période choisie" -ForegroundColor White
Write-Host ""

Write-Host "🌐 Accès:" -ForegroundColor Cyan
Write-Host "   • Frontend: http://localhost:4200" -ForegroundColor White
Write-Host "   • Backend: http://localhost:8000" -ForegroundColor White
Write-Host "   • Formulaire devis: http://localhost:4200/devis/form" -ForegroundColor White
Write-Host ""

Write-Host "🧪 Tests à effectuer:" -ForegroundColor Cyan
Write-Host "   1. Accéder au formulaire de devis" -ForegroundColor White
Write-Host "   2. Remplir les informations véhicule" -ForegroundColor White
Write-Host "   3. Choisir une compagnie et période" -ForegroundColor White
Write-Host "   4. Sélectionner des garanties" -ForegroundColor White
Write-Host "   5. Vérifier la simulation" -ForegroundColor White
Write-Host "   6. Créer le devis" -ForegroundColor White
Write-Host "   7. Vérifier la persistance en base" -ForegroundColor White
Write-Host ""

Write-Host "🔧 Commandes utiles:" -ForegroundColor Cyan
Write-Host "   • Migration: php artisan migrate" -ForegroundColor White
Write-Host "   • Seeder: php artisan db:seed --class=DevisSeeder" -ForegroundColor White
Write-Host "   • Test API: Testez les endpoints /api/devis/*" -ForegroundColor White
Write-Host ""

Write-Host "📋 Checklist de validation:" -ForegroundColor Cyan
Write-Host "   □ Formulaire multi-étapes fonctionnel" -ForegroundColor White
Write-Host "   □ Validation des champs obligatoires" -ForegroundColor White
Write-Host "   □ Upload de carte grise" -ForegroundColor White
Write-Host "   □ Sélection de compagnie" -ForegroundColor White
Write-Host "   □ Chargement des garanties par compagnie" -ForegroundColor White
Write-Host "   □ Simulation de devis" -ForegroundColor White
Write-Host "   □ Calcul automatique des tarifs" -ForegroundColor White
Write-Host "   □ Création et persistance du devis" -ForegroundColor White
Write-Host "   □ Interface responsive" -ForegroundColor White
Write-Host "   □ Respect du thème (bleu foncé + orange)" -ForegroundColor White
Write-Host ""

Write-Host "🎨 Thème appliqué:" -ForegroundColor Cyan
Write-Host "   • Couleur primaire: #151C46 (Bleu foncé)" -ForegroundColor White
Write-Host "   • Couleur secondaire: #FF6B35 (Orange)" -ForegroundColor White
Write-Host "   • Texte: Blanc sur fond bleu foncé" -ForegroundColor White
Write-Host "   • Accents: Orange pour l'interactivité" -ForegroundColor White
Write-Host ""

Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")



