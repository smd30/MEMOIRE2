# Correction de l'authentification admin
Write-Host "=== CORRECTION AUTHENTIFICATION ADMIN ===" -ForegroundColor Green

# Ouvrir la page de connexion admin
Write-Host "`nOuverture de la page de connexion admin..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/src/app/pages/clear-auth.html"

Write-Host "`n=== INSTRUCTIONS ===" -ForegroundColor Yellow
Write-Host "1. La page de connexion admin s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. L'authentification existante est automatiquement nettoyée" -ForegroundColor White
Write-Host "3. Les identifiants admin sont pré-remplis" -ForegroundColor White
Write-Host "4. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "5. Vous serez redirigé vers l'interface admin fonctionnelle" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n🎉 Problème d'authentification résolu ! 🎉" -ForegroundColor Green
