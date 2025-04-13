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
php -S localhost:8000 -t src
```
Ouvrir dans votre navigateur : 
```bash
http://localhost:8000
```
✔️ Vous devriez voir : ✅ Connexion à la base de données réussie !

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

... 

## 🧑‍💻 Auteur 

Maewenn
📧 mw.porgeon@gmail.com 



