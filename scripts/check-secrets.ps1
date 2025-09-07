Write-Host "🔐 Vérification des secrets GitHub..." -ForegroundColor Green

Write-Host "📝 Instructions pour vérifier vos secrets:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Allez sur votre repository GitHub:" -ForegroundColor Cyan
Write-Host "   https://github.com/smd30/MEMOIRE2" -ForegroundColor White
Write-Host ""
Write-Host "2. Cliquez sur 'Settings' (en haut à droite)" -ForegroundColor Cyan
Write-Host ""
Write-Host "3. Cliquez sur 'Secrets and variables' → 'Actions'" -ForegroundColor Cyan
Write-Host ""
Write-Host "4. Vérifiez que ces 5 secrets existent:" -ForegroundColor Cyan
Write-Host "   ✅ DOCKERHUB_USERNAME" -ForegroundColor Green
Write-Host "   ✅ DOCKERHUB_TOKEN" -ForegroundColor Green
Write-Host "   ✅ SONAR_TOKEN" -ForegroundColor Green
Write-Host "   ✅ RENDER_SERVICE_ID" -ForegroundColor Green
Write-Host "   ✅ RENDER_API_KEY" -ForegroundColor Green
Write-Host ""
Write-Host "5. Si un secret manque, cliquez sur 'New repository secret'" -ForegroundColor Cyan
Write-Host ""
Write-Host "6. Testez les secrets avec le workflow 'Test Secrets Configuration'" -ForegroundColor Cyan
Write-Host "   - Allez sur l'onglet 'Actions'" -ForegroundColor White
Write-Host "   - Cliquez sur 'Test Secrets Configuration'" -ForegroundColor White
Write-Host "   - Cliquez sur 'Run workflow'" -ForegroundColor White
Write-Host ""
Write-Host "🔍 Vérification des valeurs des secrets:" -ForegroundColor Yellow
Write-Host ""
Write-Host "DOCKERHUB_USERNAME:" -ForegroundColor Cyan
Write-Host "   - Doit être votre nom d'utilisateur Docker Hub" -ForegroundColor White
Write-Host "   - Exemple: smd30" -ForegroundColor White
Write-Host ""
Write-Host "DOCKERHUB_TOKEN:" -ForegroundColor Cyan
Write-Host "   - Doit être un token d'accès Docker Hub" -ForegroundColor White
Write-Host "   - Créé dans Docker Hub → Settings → Security → New Access Token" -ForegroundColor White
Write-Host ""
Write-Host "SONAR_TOKEN:" -ForegroundColor Cyan
Write-Host "   - Doit être le token SonarCloud" -ForegroundColor White
Write-Host "   - Créé dans SonarCloud → Account → Security → Generate Tokens" -ForegroundColor White
Write-Host ""
Write-Host "RENDER_SERVICE_ID:" -ForegroundColor Cyan
Write-Host "   - Doit être l'ID du service Render" -ForegroundColor White
Write-Host "   - Trouvé dans Render → Dashboard → Service → Settings" -ForegroundColor White
Write-Host ""
Write-Host "RENDER_API_KEY:" -ForegroundColor Cyan
Write-Host "   - Doit être la clé API Render" -ForegroundColor White
Write-Host "   - Créé dans Render → Account Settings → API Keys" -ForegroundColor White
Write-Host ""
Write-Host "⚠️ IMPORTANT:" -ForegroundColor Red
Write-Host "   - Les secrets sont sensibles, ne les partagez jamais" -ForegroundColor White
Write-Host "   - Si vous modifiez un secret, le pipeline se relancera automatiquement" -ForegroundColor White
Write-Host ""
Write-Host "✅ Une fois tous les secrets configurés, poussez le code:" -ForegroundColor Green
Write-Host "   git add ." -ForegroundColor White
Write-Host "   git commit -m '🔧 Fix pipeline configuration'" -ForegroundColor White
Write-Host "   git push origin main" -ForegroundColor White
