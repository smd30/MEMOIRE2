Write-Host "Test du Dockerfile local..." -ForegroundColor Green

# Verifier que Docker est disponible
if (!(Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "Docker n'est pas installe!" -ForegroundColor Red
    Write-Host "Installez Docker Desktop depuis: https://www.docker.com/products/docker-desktop" -ForegroundColor Yellow
    exit 1
}

Write-Host "Construction de l'image Docker..." -ForegroundColor Yellow
docker build -t memoire2-test -f backend/Dockerfile.simple backend

if ($LASTEXITCODE -eq 0) {
    Write-Host "Construction reussie!" -ForegroundColor Green
    
    Write-Host "Test du conteneur..." -ForegroundColor Yellow
    docker run --rm -d --name memoire2-test-container -p 9000:9000 memoire2-test
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Conteneur demarre avec succes!" -ForegroundColor Green
        Write-Host "Testez avec: docker logs memoire2-test-container" -ForegroundColor Cyan
        
        # Arreter le conteneur apres 10 secondes
        Start-Sleep -Seconds 10
        docker stop memoire2-test-container
        Write-Host "Conteneur arrete." -ForegroundColor Yellow
    } else {
        Write-Host "Erreur lors du demarrage du conteneur!" -ForegroundColor Red
    }
} else {
    Write-Host "Erreur lors de la construction!" -ForegroundColor Red
    Write-Host "Verifiez les logs ci-dessus pour plus de details." -ForegroundColor Yellow
}
