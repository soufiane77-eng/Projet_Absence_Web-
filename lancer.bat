@echo off
title Web Absence - Lancement Automatique
color 0B
cd /d "%~dp0"

:: Detection rapide des prerequis
where php >nul 2>&1 || ( echo [ERREUR] PHP 8.2+ requis & pause & exit /b 1 )
where node >nul 2>&1 || ( echo [ERREUR] Node.js 18+ requis & pause & exit /b 1 )

:: Lancer le script PowerShell
powershell -ExecutionPolicy Bypass -NoLogo -File "scripts\lancer.ps1"
exit /b %errorlevel%
