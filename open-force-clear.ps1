# Ouvrir la page de nettoyage forcé
Write-Host "=== NETTOYAGE FORCÉ AUTHENTIFICATION ===" -ForegroundColor Red

Write-Host "`n🚨 PROBLÈME : Token obsolète dans localStorage" -ForegroundColor Red
Write-Host "🔧 SOLUTION : Page de nettoyage forcé créée" -ForegroundColor Yellow

# Ouvrir la page de nettoyage forcé
Write-Host "`n🌐 Ouverture de la page de nettoyage forcé..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "`n=== INSTRUCTIONS URGENTES ===" -ForegroundColor Red
Write-Host "1. La page de nettoyage forcé s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Cliquez sur '🚀 NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "3. Vous serez redirigé vers la page de connexion" -ForegroundColor White
Write-Host "4. Entrez : admin@example.com / password" -ForegroundColor White
Write-Host "5. Vous serez redirigé vers l'interface admin fonctionnelle" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n🚨 URGENT : Utilisez cette page pour résoudre le problème ! 🚨" -ForegroundColor Red





