@echo off
title Web Absence - Lancement Automatique
color 0B
cd /d "%~dp0"

:: ============================================================
::  LANCER.BAT - Point d'entree unique du projet
::  Apres clone GitHub, il suffit de double-cliquer ici.
:: ============================================================

echo(
echo ================================================
echo    Web Absence - Pre-verification
echo    Application developpee Par Mammad Soufiane
echo ================================================
echo(

:: --- 1. VERIFICATION DES PREREQUIS --------------------------
echo [1/3] Verification des prerequis...

where php >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] PHP est introuvable.
    echo(
    echo   Pour installer PHP :
    echo   - XAMPP : https://www.apachefriends.org/
    echo   - Laragon : https://laragon.org/
    echo(
    echo   Apres installation, ajoutez PHP au PATH
    echo   ou utilisez Laragon ^(portable^).
    echo(
    pause
    exit /b 1
)
echo   [OK] PHP trouve

where node >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Node.js est introuvable.
    echo(
    echo   Telechargez-le : https://nodejs.org/ ^(version 18+^)
    echo(
    pause
    exit /b 1
)
echo   [OK] Node.js trouve

where npm >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] npm est introuvable.
    echo   npm est normalement inclus avec Node.js.
    pause
    exit /b 1
)
echo   [OK] npm trouve

:: --- 2. INSTALLATION DES DEPENDANCES ------------------------
echo(
echo [2/3] Installation des dependances...

:: PHP - Composer
if not exist "vendor\autoload.php" (
    echo(
    echo   --- Dependances PHP manquantes. Installation en cours...
    echo(
    if exist "composer.phar" (
        echo   Utilisation de composer.phar local
        php composer.phar install --no-interaction --prefer-dist
    ) else (
        where composer >nul 2>&1
        if errorlevel 1 (
            echo(
            echo   [ERREUR] Composer introuvable.
            echo   Le fichier composer.phar est manquant.
            echo(
            echo   Solution : telechargez-le depuis :
            echo   https://getcomposer.org/download/
            echo(
            echo   Placez le fichier composer.phar dans le dossier
            echo   du projet.
            echo(
            pause
            exit /b 1
        )
        echo   Utilisation de Composer global
        composer install --no-interaction --prefer-dist
    )
    if errorlevel 1 (
        echo(
        echo   [ERREUR] Echec de l'installation des dependances PHP.
        pause
        exit /b 1
    )
    echo(
    echo   [OK] Dependances PHP installees
) else (
    echo   [OK] Dependances PHP deja presentes
)

:: JS - npm
if not exist "node_modules" (
    echo(
    echo   --- Dependances JS manquantes. Installation en cours...
    echo(
    call npm install --no-audit --no-fund
    if errorlevel 1 (
        echo(
        echo   [ERREUR] Echec de npm install.
        pause
        exit /b 1
    )
    echo(
    echo   [OK] Dependances JavaScript installees
) else (
    echo   [OK] Dependances JavaScript deja presentes
)

:: --- 3. LANCEMENT DU SCRIPT PRINCIPAL -----------------------
echo(
echo [3/3] Lancement de l'application...
echo(

powershell -ExecutionPolicy Bypass -NoLogo -File "scripts\Lancer.ps1"

if errorlevel 1 (
    echo(
    echo [INFO] Le script PowerShell s'est termine.
    echo(
    pause
)

exit /b %errorlevel%
