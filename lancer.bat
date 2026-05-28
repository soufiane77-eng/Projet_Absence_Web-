@echo off
title Web Absence - Installation Automatique
color 0B
cd /d "%~dp0"

:: ============================================================
::  WEB ABSENCE - LANCEMENT AUTOMATIQUE (ONE-CLICK RUN)
::  Application de gestion des absences
::  Developpe par Mammad Soufiane
:: ============================================================

echo.
echo ================================================
echo      WEB ABSENCE - Installation et Lancement
echo ================================================
echo.

:: ---- Verification PHP ----
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] PHP 8.2+ n'est pas installe ou n'est pas dans le PATH.
    echo.
    echo   Solution : Telechargez PHP depuis https://windows.php.net/download/
    echo   Ajoutez PHP au PATH, puis relancez ce script.
    echo.
    pause
    exit /b 1
)
echo  [OK] PHP trouve :
php -v 2>&1 | findstr /b "PHP"

:: ---- Verification Composer ----
where composer >nul 2>&1
if %errorlevel% neq 0 (
    if not exist "composer.phar" (
        echo.
        echo [INFO] Composer non installe - le script va le telecharger.
        echo.
    ) else (
        echo  [OK] composer.phar present
    )
) else (
    echo  [OK] Composer trouve
)

:: ---- Verification Node.js ----
where node >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] Node.js 18+ n'est pas installe ou n'est pas dans le PATH.
    echo.
    echo   Solution : Telechargez Node.js depuis https://nodejs.org/
    echo   Ajoutez Node.js au PATH, puis relancez ce script.
    echo.
    pause
    exit /b 1
)
echo  [OK] Node.js trouve :
node -v

:: ---- Verification npm ----
where npm >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] npm n'est pas trouve (pourtant Node.js est installe ?)
    echo.
    pause
    exit /b 1
)
echo  [OK] npm trouve
echo.

:: ---- Lancement du script PowerShell principal ----
echo Lancement de l'installation...
echo.

powershell -ExecutionPolicy Bypass -NoLogo -File "scripts\lancer.ps1"
set EXIT_CODE=%errorlevel%

if %EXIT_CODE% neq 0 (
    echo.
    echo ================================================
    echo  UNE ERREUR S'EST PRODUITE - Code: %EXIT_CODE%
    echo ================================================
    echo.
    pause
    exit /b %EXIT_CODE%
)

pause
