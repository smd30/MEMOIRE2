# Test de l'interface admin - blocage
Write-Host "🧪 Test de l'interface admin - blocage..." -ForegroundColor Cyan
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "✅ Page ouverte !" -ForegroundColor Green
Write-Host "📋 Instructions :" -ForegroundColor White
Write-Host "1. Cliquez sur 'NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "2. Connectez-vous avec admin@example.com / password" -ForegroundColor White
Write-Host "3. Testez le blocage d'un utilisateur" -ForegroundColor White
Write-Host "4. Vérifiez que l'erreur 405 est corrigée" -ForegroundColor White





