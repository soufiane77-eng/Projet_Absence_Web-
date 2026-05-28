<#
.SYNOPSIS
    Script de lancement automatique du projet Web Absence (Laravel + Vite)
.DESCRIPTION
    Verifie les prerequis, installe les dependances (Composer + npm),
    configure .env, lance les migrations, et demarre les serveurs.
    Utilisation : double-clic sur lancer.bat OU :
    powershell -ExecutionPolicy Bypass -File scripts\lancer.ps1
#>

# ============================================================
#  CONFIGURATION
# ============================================================
$PROJECT_ROOT = Split-Path -Parent $PSScriptRoot
$COMPOSER_PHAR = Join-Path $PROJECT_ROOT "composer.phar"
$ENV_FILE = Join-Path $PROJECT_ROOT ".env"
$ENV_EXAMPLE = Join-Path $PROJECT_ROOT ".env.example"

# Couleurs d'affichage
$C_TITLE = "Magenta"
$C_STEP  = "Blue"
$C_INFO  = "Cyan"
$C_OK    = "Green"
$C_WARN  = "Yellow"
$C_ERR   = "Red"

# ============================================================
#  FONCTIONS
# ============================================================
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

# ── Gestion de Composer : global ou .phar ──
$script:GlobalComp = $false
function InitComp {
    if (HasCmd "composer") { $script:GlobalComp = $true; Ok "Composer global trouve"; return $true }
    if (Test-Path $COMPOSER_PHAR) { Ok "composer.phar present"; return $true }

    # Téléchargement de composer.phar
    Step "[Composer] Telechargement de composer.phar..."
    $setup = Join-Path $PROJECT_ROOT "composer-setup.php"
    $urls = @(
        "https://getcomposer.org/installer",
        "https://raw.githubusercontent.com/composer/getcomposer.org/main/web/installer"
    )
    $downloaded = $false
    foreach ($url in $urls) {
        try {
            php -r "copy('$url', '$setup');" 2>$null
            if ((Test-Path $setup) -and ((Get-Item $setup).Length -gt 1000)) {
                $downloaded = $true
                break
            }
        } catch {}
        Remove-Item $setup -ErrorAction SilentlyContinue
    }

    if (-not $downloaded) {
        # Derniere tentative: Invoke-WebRequest
        try {
            Invoke-WebRequest -Uri "https://getcomposer.org/installer" -OutFile $setup -UseBasicParsing -TimeoutSec 30
            if ((Test-Path $setup) -and ((Get-Item $setup).Length -gt 1000)) { $downloaded = $true }
        } catch { Remove-Item $setup -ErrorAction SilentlyContinue }
    }

    if ($downloaded) {
        php "$setup" --install-dir="$PROJECT_ROOT" --filename=composer.phar --quiet 2>$null
        Remove-Item $setup -ErrorAction SilentlyContinue
        if (Test-Path $COMPOSER_PHAR) { Ok "composer.phar installe avec succes"; return $true }
    }

    Err "Composer introuvable. Installez-le manuellement : https://getcomposer.org/download/"
    return $false
}

function RunComp { param([string[]]$Args)
    if ($script:GlobalComp) { & composer @Args } else { & php $COMPOSER_PHAR @Args }
}


# ============================================================
#  DEBUT DU SCRIPT
# ============================================================
$host.UI.RawUI.WindowTitle = "Web Absence - Installation"
Clear-Host

Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "     Web Absence - Installation Automatique" -ForegroundColor $C_TITLE
Write-Host "     Application de gestion des absences" -ForegroundColor $C_TITLE
Write-Host "     Developpe par Mammad Soufiane" -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "  $(Get-Date -Format 'dd/MM/yyyy HH:mm:ss')" -ForegroundColor $C_INFO
Write-Host ""

Set-Location -LiteralPath $PROJECT_ROOT

# ============================================================
#  ETAPE 1 : VERIFICATION DES PREREQUIS
# ============================================================
Step "ETAPE 1/5 : Verification des prerequis"

if (-not (HasCmd "php"))   { Err "PHP introuvable. Installez PHP 8.2+ depuis https://windows.php.net/download/" }
if (-not (HasCmd "node"))  { Err "Node.js introuvable. Installez Node.js 18+ depuis https://nodejs.org/" }
if (-not (HasCmd "npm"))   { Err "npm introuvable" }

$phpVer = php -v | Select-Object -First 1 | ForEach-Object { $_ -replace '\s+',' ' }
$nodeVer = node -v
$npmVer = npm -v

Ok "PHP : $phpVer"
Ok "Node.js : $nodeVer"
Ok "npm : $npmVer"

# ============================================================
#  ETAPE 2 : INSTALLATION DES DEPENDANCES
# ============================================================
Step "ETAPE 2/5 : Installation des dependances"

# ── PHP (Composer) ──
$vendorDir = Join-Path $PROJECT_ROOT "vendor"
$vendorCheck = Join-Path $vendorDir "autoload.php"
if (Test-Path $vendorCheck) {
    Ok "Dependances PHP deja installees (vendor/)"
} else {
    Info "Installation des dependances PHP avec Composer..."
    if (-not (InitComp)) { exit 1 }
    RunComp @("install", "--no-interaction", "--prefer-dist", "--no-progress")
    if ($LASTEXITCODE -eq 0) {
        if (Test-Path $vendorCheck) { Ok "Dependances PHP installees avec succes" }
        else { Warn "Composer termine mais vendor/autoload.php est manquant" }
    } else {
        Err "Echec de l'installation des dependances PHP (Composer)"
    }
}

# ── JavaScript (npm) ──
$npmDir = Join-Path $PROJECT_ROOT "node_modules"
if (Test-Path $npmDir) {
    Ok "Dependances JS deja installees (node_modules/)"
} else {
    Info "Installation des dependances JavaScript avec npm..."
    & npm install --no-audit --no-fund --loglevel=warn
    if ($LASTEXITCODE -eq 0) { Ok "Dependances JS installees avec succes" }
    else { Err "Echec de l'installation des dependances JS (npm)" }
}

# ============================================================
#  ETAPE 3 : CONFIGURATION DE L'ENVIRONNEMENT
# ============================================================
Step "ETAPE 3/5 : Configuration de l'environnement"

# ── .env ──
if (-not (Test-Path $ENV_FILE)) {
    if (Test-Path $ENV_EXAMPLE) {
        Copy-Item $ENV_EXAMPLE $ENV_FILE
        Ok ".env cree depuis .env.example"
    } else {
        Err ".env.example introuvable - le projet est peut-etre corrompu"
    }
} else {
    Ok ".env deja present"
}

# ── APP_KEY + Storage + Cache ──
php artisan key:generate --force 2>$null | Out-Null
if ($LASTEXITCODE -eq 0) { Ok "APP_KEY generee" } else { Warn "Echec generation APP_KEY" }

php artisan storage:link --force 2>$null | Out-Null
if ($LASTEXITCODE -eq 0) { Ok "Lien storage cree" } else { Warn "Echec lien storage (deja existant)" }

php artisan optimize:clear 2>$null | Out-Null
Ok "Cache vide"

# ============================================================
#  ETAPE 4 : BASE DE DONNEES
# ============================================================
Step "ETAPE 4/5 : Base de donnees"

# ── SQLite (si besoin) ──
$dbFile = Join-Path $PROJECT_ROOT "database\database.sqlite"
if (-not (Test-Path $dbFile)) {
    New-Item -ItemType File -Path $dbFile -Force | Out-Null
    Ok "Fichier SQLite cree"
} else {
    Ok "Base SQLite existante"
}

# ── Migrations ──
Info "Execution des migrations..."
php artisan migrate --force
if ($LASTEXITCODE -eq 0) {
    Ok "Migrations executees avec succes"
} else {
    Warn "Certaines migrations ont echoue (peut-etre deja executees)"
}

# ── Seeders ──
Info "Ajout des donnees de demonstration..."
php artisan db:seed --force 2>$null | Out-Null
if ($LASTEXITCODE -eq 0) {
    Ok "Donnees de demonstration ajoutees"
} else {
    Warn "Les seeders ont echoue (peut-etre deja executes)"
}

# ============================================================
#  ETAPE 5 : LANCEMENT DES SERVEURS
# ============================================================
Step "ETAPE 5/5 : Demarrage des serveurs"

Write-Host ""
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "    Demarrage des serveurs en cours..." -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host ""

# ── Backend Laravel ──
Info "Lancement du serveur Laravel (port 8000)..."
NewServerWin "Web Absence - Backend (Laravel)" "php artisan serve --port=8000" $PROJECT_ROOT

# ── Frontend Vite ──
Info "Lancement du serveur Vite (port 5173)..."
NewServerWin "Web Absence - Frontend (Vite)" "npm run dev" $PROJECT_ROOT

# ── Attente et ouverture navigateur ──
Write-Host ""
Write-Host "  Attente du demarrage du serveur..." -ForegroundColor $C_INFO
if (WaitPort 8000 15) {
    Ok "Serveur pret sur http://127.0.0.1:8000"
    try {
        Start-Process "http://127.0.0.1:8000"
        Ok "Navigateur ouvert"
    } catch {
        Warn "Impossible d'ouvrir le navigateur automatiquement"
    }
} else {
    Warn "Le serveur prend plus de temps que prevu."
    Warn "Verifiez les fenetres PowerShell ouvertes."
}

# ── Affichage final ──
Write-Host ""
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host "    INSTALLATION TERMINEE AVEC SUCCES !" -ForegroundColor $C_TITLE
Write-Host "" -ForegroundColor $C_TITLE
Write-Host "    Application : http://127.0.0.1:8000" -ForegroundColor $C_TITLE
Write-Host "    Frontend    : http://127.0.0.1:5173" -ForegroundColor $C_TITLE
Write-Host "" -ForegroundColor $C_TITLE
Write-Host "    Identifiants de connexion :" -ForegroundColor $C_TITLE
Write-Host "    Admin       : admin@etu.uae.ac.ma / admin" -ForegroundColor $C_TITLE
Write-Host "    Etudiant    : etudiant@test.com / password" -ForegroundColor $C_TITLE
Write-Host "    Professeur  : professeur@test.com / password" -ForegroundColor $C_TITLE
Write-Host "" -ForegroundColor $C_TITLE
Write-Host "    Pour arreter : fermez les 2 fenetres PowerShell" -ForegroundColor $C_TITLE
Write-Host "    qui viennent de s'ouvrir (Backend et Frontend)" -ForegroundColor $C_TITLE
Write-Host "================================================" -ForegroundColor $C_TITLE
Write-Host ""
Write-Host "  Appuyez sur une touche pour fermer cette fenetre..." -ForegroundColor $C_INFO
