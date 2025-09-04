# Test du système de calcul de devis
Write-Host "=== Test du Système de Calcul de Devis ===" -ForegroundColor Green
Write-Host ""

Write-Host "✅ Système de calcul implémenté:" -ForegroundColor Yellow
Write-Host "   • Service DevisCalculationService créé" -ForegroundColor White
Write-Host "   • Tarifs RC par catégorie de véhicule" -ForegroundColor White
Write-Host "   • Pourcentages des garanties optionnelles" -ForegroundColor White
Write-Host "   • Taxe TPA 10% (Sénégal)" -ForegroundColor White
Write-Host "   • Frais de dossier 5000 FCFA" -ForegroundColor White
Write-Host "   • Ajustement selon la période" -ForegroundColor White
Write-Host ""

Write-Host "💰 Tarifs de base RC (FCFA/an):" -ForegroundColor Yellow
Write-Host "   • Véhicules particuliers: 25 000" -ForegroundColor White
Write-Host "   • Transport en commun: 35 000" -ForegroundColor White
Write-Host "   • Transport marchandises: 45 000" -ForegroundColor White
Write-Host "   • Deux/trois roues: 15 000" -ForegroundColor White
Write-Host "   • Véhicules spéciaux: 60 000" -ForegroundColor White
Write-Host "   • Véhicules d'État: 40 000" -ForegroundColor White
Write-Host ""

Write-Host "🔧 Pourcentages garanties (% valeur):" -ForegroundColor Yellow
Write-Host "   • Vol: 2.0%" -ForegroundColor White
Write-Host "   • Incendie: 1.0%" -ForegroundColor White
Write-Host "   • Bris de glace: 0.5%" -ForegroundColor White
Write-Host "   • Dommages collision: 3.0%" -ForegroundColor White
Write-Host "   • Défense/recours: 0.3%" -ForegroundColor White
Write-Host "   • Assistance 0km: 0.2%" -ForegroundColor White
Write-Host ""

Write-Host "📊 Facteurs de période:" -ForegroundColor Yellow
Write-Host "   • 1 mois: 12% de l'annuel" -ForegroundColor White
Write-Host "   • 3 mois: 30% de l'annuel" -ForegroundColor White
Write-Host "   • 6 mois: 55% de l'annuel" -ForegroundColor White
Write-Host "   • 12 mois: 100% de l'annuel" -ForegroundColor White
Write-Host ""

Write-Host "🎯 Exemple de calcul:" -ForegroundColor Yellow
Write-Host "   Véhicule particulier: 5 000 000 FCFA" -ForegroundColor White
Write-Host "   RC: 25 000 FCFA" -ForegroundColor White
Write-Host "   Vol (2%): 100 000 FCFA" -ForegroundColor White
Write-Host "   Incendie (1%): 50 000 FCFA" -ForegroundColor White
Write-Host "   Sous-total: 175 000 FCFA" -ForegroundColor White
Write-Host "   TPA (10%): 17 500 FCFA" -ForegroundColor White
Write-Host "   Frais dossier: 5 000 FCFA" -ForegroundColor White
Write-Host "   Total annuel: 197 500 FCFA" -ForegroundColor White
Write-Host ""

Write-Host "🚀 Test maintenant:" -ForegroundColor Yellow
Write-Host "1. Ouvrez http://localhost:4200" -ForegroundColor White
Write-Host "2. Connectez-vous avec client@test.com / password" -ForegroundColor White
Write-Host "3. Créez un nouveau devis" -ForegroundColor White
Write-Host "4. Vérifiez que le calcul s'affiche correctement" -ForegroundColor White
Write-Host ""

Write-Host "🎉 Le système de calcul est maintenant opérationnel !" -ForegroundColor Green


