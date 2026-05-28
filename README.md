# 🎓 Projet Absence Web

Application de gestion des absences pour établissements d'enseignement supérieur.
Développée avec **Laravel 12**, **SQLite**, **Bootstrap** et **Vite**.

## Fonctionnalités

- **Trois rôles** : Administrateur, Enseignant, Étudiant
- **Gestion des filières, classes, modules, éléments, semestres**
- **Marquage des absences** par séance (enseignant)
- **Justifications** d'absences avec fichiers joints
- **Réclamations** des étudiants
- **Tableau de bord** avec statistiques par rôle
- **Historique** des connexions et activités
- **Captcha** (Mews) pour la connexion

## Prérequis

| Logiciel | Version |
|----------|---------|
| PHP | 8.2+ |
| Node.js | 18+ |
| npm | 9+ |
| Composer | 2.x |

Extensions PHP requises : `bcmath`, `curl`, `gd`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_sqlite`, `xml`, `zip`

## Installation rapide

```bash
# 1. Cloner le dépôt
git clone https://github.com/soufiane77-eng/Projet_Absence_Web-.git
cd Projet_Absence_Web-

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JavaScript
npm install

# 4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Créer la base de données
touch database/database.sqlite
php artisan migrate --seed

# 6. Builder les assets frontend
npm run build

# 7. Lancer le serveur
php artisan serve
```

Accédez à l'application : **http://127.0.0.1:8000**

## Lancement automatique (Windows)

Double-cliquez simplement sur **`lancer.bat`** — il s'occupe de tout :
vérification des prérequis, installation des dépendances, configuration,
migrations, seeders, et lancement des serveurs.

```bash
.\lancer.bat
```

## Identifiants par défaut

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@etu.uae.ac.ma | admin |

*(Les comptes enseignant et étudiant sont créés via l'interface admin)*

## Structure du projet

```
├── app/
│   ├── Http/Controllers/    # Contrôleurs (Admin, Teacher, Student, Auth)
│   ├── Models/              # 15 modèles Eloquent
│   └── Providers/
├── config/                  # Configuration Laravel
├── database/
│   ├── migrations/          # 23 migrations
│   └── seeders/             # 4 seeders
├── resources/
│   └── views/               # Templates Blade
├── routes/
│   ├── admin.php            # Routes admin (85+ routes)
│   ├── teacher.php          # Routes enseignant
│   └── student.php          # Routes étudiant
├── scripts/
│   └── lancer.ps1           # Script de lancement PowerShell
├── lancer.bat               # Lanceur Windows (batch)
├── composer.json
├── package.json
└── vite.config.js
```

## Commandes utiles

```bash
# Mode développement (serveurs PHP + Vite)
npm run dev          # Frontend HMR
php artisan serve    # Backend

# Base de données
php artisan migrate:fresh --seed    # Réinitialiser + seed
php artisan db:seed                  # Re-seeder uniquement

# Maintenance
php artisan optimize:clear          # Vider le cache
php artisan config:cache            # Cache de config
```

## Licence

Projet développé par **Mammad Soufiane**.
