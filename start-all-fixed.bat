@echo off
echo 🚀 Démarrage de la plateforme d'assurance automobile (Version Corrigée)
echo ================================================================
echo.

echo 📋 Vérification des prérequis...
echo.

REM Vérifier PHP
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP n'est pas installé
    echo 📥 Téléchargez PHP depuis https://windows.php.net/download/
    pause
    exit /b 1
)

REM Vérifier Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer n'est pas installé
    echo 📥 Téléchargez Composer depuis https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Vérifier Node.js
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Node.js n'est pas installé
    echo 📥 Téléchargez Node.js depuis https://nodejs.org/
    pause
    exit /b 1
)

REM Vérifier npm
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ npm n'est pas installé
    pause
    exit /b 1
)

echo ✅ Tous les prérequis sont installés
echo.

echo 🗄️ Configuration de la base de données...
echo ⚠️  Assurez-vous que MySQL est démarré et accessible
echo    - Host: localhost
echo    - Port: 3306
echo    - User: root
echo    - Password: passer
echo    - Database: assurance_auto
echo.

set /p confirm="Voulez-vous continuer ? (o/n): "
if /i not "%confirm%"=="o" (
    echo ❌ Démarrage annulé
    pause
    exit /b 1
)

echo.
echo 🚀 Démarrage des serveurs avec réparation automatique...
echo.

REM Démarrer le backend dans une nouvelle fenêtre avec réparation Sanctum
echo 📡 Démarrage du serveur backend Laravel (avec réparation Sanctum)...
start "Backend Laravel" cmd /k "cd backend && start-fixed.bat"

REM Attendre un peu pour que le backend démarre
echo ⏳ Attente du démarrage du backend...
timeout /t 10 /nobreak >nul

REM Démarrer le frontend dans une nouvelle fenêtre
echo 🌐 Démarrage du serveur frontend Angular...
start "Frontend Angular" cmd /k "cd frontend && start.bat"

echo.
echo ✅ Les serveurs sont en cours de démarrage...
echo.
echo 📋 Informations d'accès :
echo    🌐 Frontend: http://localhost:4200
echo    📡 Backend API: http://localhost:8000/api
echo    🔍 Health check: http://localhost:8000/api/health
echo.
echo 🔐 Compte de test :
echo    Email: test@example.com
echo    Mot de passe: password
echo.
echo ⏳ Attendez quelques secondes que les serveurs démarrent complètement
echo.
echo 🧪 Test de connexion via Postman :
echo    POST http://localhost:8000/api/auth/login
echo    {
echo      "email": "test@example.com",
echo      "password": "password"
echo    }
echo.
echo ⏹️  Fermez cette fenêtre pour arrêter tous les serveurs
echo.

pause
