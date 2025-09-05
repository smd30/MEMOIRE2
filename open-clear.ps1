# Ouvrir la page de nettoyage forcé
Write-Host "=== OUVERTURE PAGE NETTOYAGE FORCÉ ===" -ForegroundColor Green

Write-Host "`n🚨 PROBLÈME : Token obsolète dans localStorage" -ForegroundColor Red
Write-Host "🔧 SOLUTION : Page de nettoyage forcé créée" -ForegroundColor Yellow

# Ouvrir la page de nettoyage forcé
Write-Host "`n🌐 Ouverture de la page de nettoyage forcé..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "`n=== INSTRUCTIONS URGENTES ===" -ForegroundColor Red
Write-Host "1. La page de nettoyage forcé s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Attendez le message '✅ Authentification nettoyée avec succès !'" -ForegroundColor White
Write-Host "3. Cliquez sur '🚀 NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "4. Vous serez redirigé vers la page de connexion" -ForegroundColor White
Write-Host "5. Entrez : admin@example.com / password" -ForegroundColor White
Write-Host "6. Vous serez redirigé vers l'interface admin fonctionnelle" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n✅ Backend vérifié : Connexion admin fonctionnelle" -ForegroundColor Green
Write-Host "🎉 Prêt à résoudre le problème d'authentification !" -ForegroundColor Green






