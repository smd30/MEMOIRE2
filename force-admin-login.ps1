# Force la connexion admin avec nettoyage complet
Write-Host "=== FORCE CONNEXION ADMIN ===" -ForegroundColor Green

Write-Host "`n1. Nettoyage de l'authentification existante..." -ForegroundColor Yellow

# Ouvrir la page de connexion admin qui nettoie automatiquement
Write-Host "2. Ouverture de la page de connexion admin..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/src/app/pages/clear-auth.html"

# Attendre un peu puis ouvrir aussi l'interface normale pour forcer le nettoyage
Start-Sleep -Seconds 2
Write-Host "3. Ouverture de l'interface principale..." -ForegroundColor Yellow
Start-Process "http://localhost:4200"

Write-Host "`n=== INSTRUCTIONS DÉTAILLÉES ===" -ForegroundColor Yellow
Write-Host "1. La page de connexion admin s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Attendez que le message '✅ Authentification nettoyée' apparaisse" -ForegroundColor White
Write-Host "3. Les identifiants admin sont pré-remplis: admin@example.com / password" -ForegroundColor White
Write-Host "4. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "5. Vous serez redirigé vers l'interface admin fonctionnelle" -ForegroundColor White

Write-Host "`n=== ALTERNATIVE ===" -ForegroundColor Cyan
Write-Host "Si la page admin ne s'ouvre pas, utilisez l'interface normale:" -ForegroundColor White
Write-Host "1. Cliquez sur 'Se Connecter' dans l'interface principale" -ForegroundColor White
Write-Host "2. Entrez: admin@example.com / password" -ForegroundColor White
Write-Host "3. Vous serez redirigé vers l'interface admin" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n🎉 Prêt à tester l'interface admin ! 🎉" -ForegroundColor Green








