<#
.SYNOPSIS
    Script de lancement automatique du projet Web Absence (Laravel + Vite)
.DESCRIPTION
    Installe les dependances, configure l'environnement et lance les serveurs.
    Utilisation : double-clic sur lancer.bat OU :
    powershell -ExecutionPolicy Bypass -File scripts\lancer.ps1
#>

# ─── CONFIGURATION ───────────────────────────────────────────
$PROJECT_ROOT = Split-Path -Parent $PSScriptRoot
$COMPOSER_PHAR = Join-Path $PROJECT_ROOT "composer.phar"
$ENV_FILE = Join-Path $PROJECT_ROOT ".env"
$ENV_EXAMPLE = Join-Path $PROJECT_ROOT ".env.example"
$DB_FILE = Join-Path $PROJECT_ROOT "database\database.sqlite"

# Couleurs
$C_INFO  = "Cyan"
$C_OK    = "Green"
$C_WARN  = "Yellow"
$C_ERR   = "Red"
$C_TITLE = "Magenta"
$C_STEP  = "Blue"

# ─── FONCTIONS RAPIDES ────────────────────────────────────────
function Step($m)  { Write-Host "`n>>> $m" -ForegroundColor $C_STEP }
function Info($m)  { Write-Host "  $m" -ForegroundColor $C_INFO }
function Ok($m)    { Write-Host "  [OK] $m" -ForegroundColor $C_OK }
function Warn($m)  { Write-Host "  [!] $m" -ForegroundColor $C_WARN }
function Err($m)   { Write-Host "  [X] $m" -ForegroundColor $C_ERR; exit 1 }

function HasCmd($c) { return [bool](Get-Command $c -ErrorAction SilentlyContinue) }

function WaitPort($port, $timeout = 20) {
    $elapsed = 0
    while ($elapsed -lt $timeout) {
        try {
            $c = New-Object System.Net.Sockets.TcpClient
            $r = $c.BeginConnect("127.0.0.1", $port, $null, $null)
            if ($r.AsyncWaitHandle.WaitOne(300, $false)) { $c.EndConnect($r); $c.Close(); return $true }
            $c.Close()
        } catch {}
        Start-Sleep -Milliseconds 300; $elapsed += 0.3
    }
    return $false
}

function NewServerWin($title, $command, $workdir) {
    $scriptBlock = @"
`$host.UI.RawUI.WindowTitle = '$title'
Write-Host '[Web Absence] $title' -ForegroundColor Cyan
while (`$true) {
    try { cd '$workdir'; $command; Start-Sleep 1 }
    catch { Write-Host "[!] Exception : `$_" -ForegroundColor Red }
    Write-Host "[!] Redemarrage dans 3s..." -ForegroundColor Yellow
    Start-Sleep 3
}
"@
    $enc = [Convert]::ToBase64String([Text.Encoding]::Unicode.GetBytes($scriptBlock))
    $psi = New-Object System.Diagnostics.ProcessStartInfo
    $psi.FileName = "powershell.exe"
    $psi.Arguments = "-NoExit -EncodedCommand $enc"
    $psi.WindowStyle = "Normal"
    $psi.UseShellExecute = $true
    [void][System.Diagnostics.Process]::Start($psi)
}

$script:GlobalComp = $false
function InitComp {
    if (HasCmd "composer") { $script:GlobalComp = $true; Ok "Composer global"; return $true }
    if (Test-Path $COMPOSER_PHAR) { Ok "composer.phar present"; return $true }
    Info "Telechargement composer.phar..."
    $setup = Join-Path $PROJECT_ROOT "composer-setup.php"
    try {
        php -r "copy('https://getcomposer.org/installer', '$setup');"
        if (Test-Path $setup) {
            php "$setup" --install-dir="$PROJECT_ROOT" --filename=composer.phar --quiet 2>$null
            Remove-Item $setup -ErrorAction SilentlyContinue
        }
    } catch {
        Remove-Item $setup -ErrorAction SilentlyContinue
    }
    if (Test-Path $COMPOSER_PHAR) { Ok "composer.phar installe"; return $true }
    Err "Composer introuvable. Installez-le : https://getcomposer.org/download/"
    return $false
}

function RunComp { param([string[]]$Args)
    if ($script:GlobalComp) { & composer @Args } else { & php $COMPOSER_PHAR @Args }
}


# ─── DEBUT ────────────────────────────────────────────────────
$host.UI.RawUI.WindowTitle = "Web Absence - Installation"
Clear-Host

Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "     Web Absence - Lancement Automatique" -ForegroundColor $C_TITLE
Write-Host "     Application developpe Par Mammad Soufiane" -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "  $(Get-Date -Format 'dd/MM/yyyy HH:mm:ss')" -ForegroundColor $C_INFO
Write-Host ""

Set-Location -LiteralPath $PROJECT_ROOT

# ─── 1. PREREQUIS + ENV (preparatifs rapides) ────────────────
Step "ETAPE 1/5 : Verification des prerequis"
if (-not (HasCmd "php"))   { Err "PHP introuvable. Installez PHP 8.2+" }
if (-not (HasCmd "node"))  { Err "Node.js introuvable. Installez Node.js 18+" }
if (-not (HasCmd "npm"))   { Err "npm introuvable" }
Ok "PHP $(php -v | Select-Object -First 1 | ForEach-Object { $_ -replace '\s+',' ' })"
Ok "Node.js $(node -v) / npm $(npm -v)"

# ─── 2. DEPENDANCES PHP + JS (en parallele) ───────────────────
Step "ETAPE 2/5 : Installation des dependances"

# PHP
if (Test-Path (Join-Path $PROJECT_ROOT "vendor\autoload.php")) {
    Ok "Dependances PHP deja installees"
} else {
    Info "Installation des dependances PHP (Composer)..."
    if (-not (InitComp)) { exit 1 }
    RunComp @("install", "--no-interaction", "--prefer-dist", "--no-dev", "--quiet")
    if ($LASTEXITCODE -eq 0) { Ok "Dependances PHP installees" } else { Warn "Composer a rencontre des avertissements" }
}

# JS
if (Test-Path (Join-Path $PROJECT_ROOT "node_modules\.package-lock.json")) {
    Ok "Dependances JS deja installees"
} else {
    Info "Installation des dependances JavaScript (npm)..."
    & npm install --no-audit --no-fund --loglevel=error
    if ($LASTEXITCODE -eq 0) { Ok "Dependances JS installees" } else { Err "Erreur npm install" }
}

# ─── 3. CONFIGURATION .env + APP_KEY + STORAGE ──────────────
Step "ETAPE 3/5 : Configuration de l'environnement"

if (-not (Test-Path $ENV_FILE)) {
    if (Test-Path $ENV_EXAMPLE) { Copy-Item $ENV_EXAMPLE $ENV_FILE; Ok ".env cree" }
    else { Err ".env.example introuvable" }
} else { Ok ".env deja present" }

# APP_KEY + storage + cache = 3 artisan commands en 1 operation
php artisan key:generate --force 2>$null | Out-Null
php artisan storage:link --force 2>$null | Out-Null
php artisan optimize:clear 2>$null | Out-Null
Ok "APP_KEY / lien storage / cache configures"

# ─── 4. BASE DE DONNEES ─────────────────────────────────────
Step "ETAPE 4/5 : Base de donnees"

if (-not (Test-Path $DB_FILE)) {
    New-Item -ItemType File -Path $DB_FILE -Force | Out-Null
    Info "Fichier SQLite cree"
}

php artisan migrate --force
if ($LASTEXITCODE -eq 0) { Ok "Migrations OK" } else { Warn "Migrations partiellement echouees" }

php artisan db:seed --force 2>$null | Out-Null
Ok "Donnees de demonstration ajoutees"

# ─── 5. LANCEMENT ────────────────────────────────────────────
Step "ETAPE 5/5 : Demarrage des serveurs"

Write-Host ""
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "    Demarrage en cours..." -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host ""

# Backend
Info "Lancement du serveur Laravel..."
NewServerWin "Web Absence - Backend (Laravel)" "php artisan serve --port=8000" $PROJECT_ROOT

# Frontend
Info "Lancement du serveur Vite..."
NewServerWin "Web Absence - Frontend (Vite)" "npm run dev" $PROJECT_ROOT

# Attente + navigateur
if (WaitPort 8000 15) {
    Ok "Serveur pret : http://127.0.0.1:8000"
    try { Start-Process "http://127.0.0.1:8000"; Ok "Navigateur ouvert" } catch {}
} else {
    Warn "Le serveur prend plus de temps. Verifiez les fenetres ouvertes."
}

Write-Host ""
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "    PROJET LANCE AVEC SUCCES !" -ForegroundColor $C_TITLE
Write-Host "" -ForegroundColor $C_TITLE
Write-Host "    Application : http://127.0.0.1:8000" -ForegroundColor $C_TITLE
Write-Host "    Frontend Vite : http://127.0.0.1:5173" -ForegroundColor $C_TITLE
Write-Host "    Admin : admin@etu.uae.ac.ma / admin" -ForegroundColor $C_TITLE
Write-Host "" -ForegroundColor $C_TITLE
Write-Host "    Pour arreter : fermez les 2 fenetres PowerShell" -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host ""
Warn "  Fermeture automatique dans 10 secondes..."
Start-Sleep 10
