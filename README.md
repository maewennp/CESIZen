# 📦 Guide d'installation Projet CESIZen


## Backend API CESIZen

Cette partie du guide permet l'installation de l'API backend du projet **CESIZen**, développé en PHP natif avec une base de données MySQL hébergée dans un conteneur Docker.

---

### ⚙️ Prérequis

Avant de démarrer, assurez-vous d'avoir installé les éléments suivants :

- ✅ [PHP 8.1+](https://www.php.net/downloads.php) installé en local et ajouté à votre `PATH`
- ✅ [Composer](https://getcomposer.org/download/) installé pour gérer les dépendances PHP
- ✅ [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé
- ✅ (Optionnel) [DBeaver](https://dbeaver.io/) pour visualiser la base de données

---

### 🗂️ Arborescence du backend

```bash
backend/
├── api/                
├── docker/             # Docker + base MySQL
│   ├── .env            # Variables d'environnement docker
│   └── docker-compose.yml
├── sql/                # Scripts SQL : création + peuplement
│   ├── creation_db.sql
│   └── peuplement_db.sql
├── src/                # Point d'entrée (pour checker la connexion à la DB)
│   └── index.php
├── .env                # Variables d'environnement
├── .env.example        # Variables d'environnement exemple
├── composer.json       # Dépendances PHP
├── composer.lock
└── database.php        # Connexion PDO à la base

```
### 🚀 Étapes d'installation 

**1. Cloner le dépôt**
```bash
git clone https://github.com/maewennp/CESIZen.git
cd CESIZen/backend
```

**2. Lancer la base de données MySQL avec Docker**
```bash
cd docker
docker-compose up -d
```
✔️ Cela crée une base nommée cesizen avec un utilisateur cesizen_user.

**3. Installer les dépendances PHP**

Revenez à la racine du dossier backend/ :
```bash
cd ..
composer install
```
💡 Si vous voyez une erreur liée à openssl ou curl, vérifiez votre configuration PHP (php.ini).

**4. Créer le fichier .env**

Copiez le fichier .env.example pour créer un nouveau fichier .env :
```env
# Base de données 
DB_HOST=127.0.0.1
DB_PORT=3307
DB_NAME=cesizen
DB_USER=your_db_user
DB_PASS=your_secure_password
```

**5. Lancer le serveur PHP**
```bash
cd backend
php -S localhost:8000 -t .
```

---

### 🧪 Tester la base de données

Si vous souhaitez visualiser ou modifier la base de données : 
1. Ouvrez DBeaver (ou autre)
2. Nouvelle connexion MySQL
```bash
Hôte : localhost
Port : 3307
Utilisateur : cf .env.example
Mot de passe : cf .env.example
```

---

## Frontend CESIZen

Cette partie du guide permet l'installation et le lancement du frontend du projet CESIZen, développé avec Vue.js 3, TypeScript, Vuetify, et propulsé par Vite.
Le projet est également optimisé en Progressive Web App (PWA) pour offrir une expérience fluide sur mobile, tablette et desktop.

---

### ⚙️ Prérequis

Avant de démarrer, assurez-vous d'avoir installé les éléments suivants :

- ✅ Node.js 18+ installé en local
- ✅ npm 9+ (inclus avec Node.js)
- ✅ (Optionnel) Vue Devtools pour faciliter le débogage Vue 3
- ✅ (Optionnel) Navigateur Chrome ou Edge pour tester la PWA

---

### 🗂️ Arborescence du frontend

```bash
frontend/
├── public/               # Fichiers publics (manifest.json, icons, etc.)
├── src/
│   ├── assets/           # Images et ressources
│   ├── components/       # Composants Vue réutilisables
│   ├── views/            # Pages principales (Accueil, Login, Relaxation, etc.)
│   ├── router/           # Configuration du router Vue
│   ├── composables/      # Fonctions réutilisables (si besoin)
│   └── App.vue           # Composant racine
├── .env                  # Variables d'environnement
├── index.html            # Point d'entrée HTML
├── package.json          # Dépendances npm
├── vite.config.ts        # Configuration Vite
└── tsconfig.app.json     # Configuration TypeScript
```

### 🚀 Étapes d'installation 

**1. Cloner le dépôt (si ce n'est pas déjà fait avec la partie installation backend)**
```bash
git clone https://github.com/maewennp/CESIZen.git
cd CESIZen/frontend
```

**2. Installer les dépendances npm**
```bash
npm install
```

**3. Lancer l'application en mode développement**

```bash
npm run dev
```
✔️ Cela démarre l'application sur http://localhost:5173.


### 📦 Construire l'application pour la production 

Pour générer une version optimisée et prête à être déployée :
```bash
npm run build
```
✔️ Les fichiers seront compilés dans le dossier /dist.


### 🧩 Fonctionnalités PWA intégrées 

📱 Installation rapide : possibilité d'installer l'application sur mobile et desktop.

🔌 Mode hors-ligne : ressources critiques mises en cache.

⚡ Chargement rapide : pré-caching des ressources essentielles.

🎨 Manifest Web App : configuration complète (nom, icônes, couleur, etc.).

🔐 HTTPS obligatoire : recommandé pour utiliser toutes les fonctionnalités PWA.

ℹ️ L'activation de la PWA est automatique lors du npm run build, sans configuration supplémentaire.


### 🌟 Conseils

Utilisez Ctrl + Maj + P → "Service Workers : Update" dans Chrome DevTools pour tester la PWA.

Pensez à réinitialiser le cache PWA si vous mettez à jour votre frontend.

--- 

## 🧑‍💻 Auteur 

Maewenn
📧 mw.porgeon@gmail.com 



