@echo off
echo 🚀 Démarrage du serveur Laravel...
echo.

cd /d "%~dp0"

echo 📁 Répertoire de travail: %CD%
echo.

echo 🔧 Vérification de la configuration...
php check-email-config.php
echo.

echo 🌐 Démarrage du serveur sur http://localhost:8000
echo.
php artisan serve --host=0.0.0.0 --port=8000
