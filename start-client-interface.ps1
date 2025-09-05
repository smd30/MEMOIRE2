# Script pour démarrer l'interface client complète basée sur les cas d'usage UML
Write-Host "=== Interface Client KDSAssur - Basée sur les Cas d'Usage UML ===" -ForegroundColor Green
Write-Host ""

Write-Host "🎯 Cas d'Usage Implémentés:" -ForegroundColor Yellow
Write-Host "   ✅ creer un compte (s'authentifier)" -ForegroundColor Cyan
Write-Host "   ✅ Demander un devis (renseigner les infos + choisir compagnies & garanties)" -ForegroundColor Cyan
Write-Host "   ✅ souscrire à une assurance (payer + télécharger l'attestation)" -ForegroundColor Cyan
Write-Host "   ✅ RENOUVELER UN CONTRAT" -ForegroundColor Cyan
Write-Host "   ✅ declarer un sinistre" -ForegroundColor Cyan
Write-Host "   ✅ consulter ses contrats" -ForegroundColor Cyan
Write-Host ""

Write-Host "📋 Pages Disponibles:" -ForegroundColor Yellow
Write-Host "   • Dashboard Client - Tableau de bord principal" -ForegroundColor White
Write-Host "   • Formulaire Devis - Processus en 3 étapes" -ForegroundColor White
Write-Host "   • Résultats Devis - Comparaison des propositions" -ForegroundColor White
Write-Host "   • Souscription - Processus de paiement" -ForegroundColor White
Write-Host "   • Contrats - Gestion et renouvellement" -ForegroundColor White
Write-Host "   • Sinistres - Déclaration et suivi" -ForegroundColor White
Write-Host "   • Attestations - Téléchargement" -ForegroundColor White
Write-Host "   • Profil - Informations utilisateur" -ForegroundColor White
Write-Host ""

# Vérifier si les serveurs sont en cours d'exécution
Write-Host "🔍 Vérification des serveurs..." -ForegroundColor Yellow

$backendRunning = $false
$frontendRunning = $false

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET -TimeoutSec 5
    if ($response.status -eq "ok") {
        $backendRunning = $true
        Write-Host "   ✅ Backend Laravel: http://localhost:8000" -ForegroundColor Green
    }
} catch {
    Write-Host "   ❌ Backend Laravel: Non démarré" -ForegroundColor Red
}

try {
    $response = Invoke-RestMethod -Uri "http://localhost:4200" -Method GET -TimeoutSec 5
    $frontendRunning = $true
    Write-Host "   ✅ Frontend Angular: http://localhost:4200" -ForegroundColor Green
} catch {
    Write-Host "   ❌ Frontend Angular: Non démarré" -ForegroundColor Red
}

Write-Host ""

# Démarrer les serveurs si nécessaire
if (-not $backendRunning) {
    Write-Host "🚀 Démarrage du Backend Laravel..." -ForegroundColor Yellow
    Start-Process powershell -ArgumentList "-Command", "cd backend; php artisan serve --host=0.0.0.0 --port=8000" -WindowStyle Normal
    Write-Host "   ⏳ Attente du démarrage du backend..." -ForegroundColor Cyan
    Start-Sleep -Seconds 5
}

if (-not $frontendRunning) {
    Write-Host "🚀 Démarrage du Frontend Angular..." -ForegroundColor Yellow
    Start-Process powershell -ArgumentList "-Command", "cd frontend; npm start" -WindowStyle Normal
    Write-Host "   ⏳ Attente du démarrage du frontend..." -ForegroundColor Cyan
    Start-Sleep -Seconds 10
}

Write-Host ""
Write-Host "=== Interface Client Prête ===" -ForegroundColor Green
Write-Host ""

Write-Host "🔗 Accès à l'Interface:" -ForegroundColor Yellow
Write-Host "   • Page d'accueil: http://localhost:4200" -ForegroundColor Cyan
Write-Host "   • Connexion: http://localhost:4200/login" -ForegroundColor Cyan
Write-Host "   • Inscription: http://localhost:4200/register" -ForegroundColor Cyan
Write-Host "   • Dashboard: http://localhost:4200/dashboard" -ForegroundColor Cyan
Write-Host "   • Nouveau Devis: http://localhost:4200/devis/nouveau" -ForegroundColor Cyan
Write-Host ""

Write-Host "🎯 Workflow Utilisateur:" -ForegroundColor Yellow
Write-Host "   1. Inscription/Connexion" -ForegroundColor White
Write-Host "   2. Dashboard → Nouveau Devis" -ForegroundColor White
Write-Host "   3. Remplir le formulaire (3 étapes)" -ForegroundColor White
Write-Host "   4. Consulter les propositions" -ForegroundColor White
Write-Host "   5. Souscrire et payer" -ForegroundColor White
Write-Host "   6. Télécharger l'attestation" -ForegroundColor White
Write-Host "   7. Gérer contrats et sinistres" -ForegroundColor White
Write-Host ""

Write-Host "📱 Fonctionnalités Mobile:" -ForegroundColor Yellow
Write-Host "   • Design responsive" -ForegroundColor White
Write-Host "   • Menu mobile adaptatif" -ForegroundColor White
Write-Host "   • Formulaires optimisés" -ForegroundColor White
Write-Host ""

Write-Host "🎨 Design System:" -ForegroundColor Yellow
Write-Host "   • Couleurs: Bleu (#1e3a8a) + Orange (#f97316)" -ForegroundColor White
Write-Host "   • Typographie moderne" -ForegroundColor White
Write-Host "   • Animations fluides" -ForegroundColor White
Write-Host "   • États de chargement" -ForegroundColor White
Write-Host ""

Write-Host "✅ Interface client complète basée sur les cas d'usage UML !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")



