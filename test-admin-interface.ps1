# Test de l'interface admin
Write-Host "🧪 Test de l'interface admin..." -ForegroundColor Cyan

# Attendre que les serveurs soient prêts
Write-Host "⏳ Attente des serveurs..." -ForegroundColor Yellow
Start-Sleep -Seconds 5

# Ouvrir la page de nettoyage d'authentification
Write-Host "🌐 Ouverture de la page de nettoyage..." -ForegroundColor Green
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "✅ Interface admin prête !" -ForegroundColor Green
Write-Host "📋 Instructions :" -ForegroundColor White
Write-Host "1. Cliquez sur 'NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "2. Connectez-vous avec admin@example.com / password" -ForegroundColor White
Write-Host "3. Vous devriez être redirigé vers l'interface admin" -ForegroundColor White
Write-Host "4. Testez la gestion des utilisateurs" -ForegroundColor White



