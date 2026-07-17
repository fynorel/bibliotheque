ce projet est un outil de gestion de bibliothèque personnelle.
## 🛠️ Stack technique

| Technologie     | Version         |
|-----------------|-----------------|
| PHP             | 8.5.6           |
| Symfony         | 7.4.11          |
| MariaDB         | 11.8.6          |
| MongoDB         | 7.0.34          |
| Bootstrap       | 5.3.0           |

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

- **PHP** >= 8.2 avec les extensions : `pdo`, `pdo_mysql`, `mongodb`, `intl`, `mbstring`, `xml`
- **Composer** >= 2.x
- **MariaDB** ou MySQL >= 10.x
- **MongoDB** >= 7.x
- **Symfony CLI** (optionnel mais recommandé)
- **Git**
## 🚀 Installation en local

### 1. Cloner le dépôt

```bash
git clone https://github.com/VOTRE_USERNAME/vite_gourmand.git
cd vite_gourmand
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer les variables d'environnement

Copiez le fichier `.env` et adaptez-le :

```bash
cp .env .env.local
```

Éditez `.env.local` avec vos paramètres :

```env
# Base de données MariaDB
DATABASE_URL="mysql://VOTRE_USER:VOTRE_MOT_DE_PASSE@127.0.0.1:3306/bibliotheque?serverVersion=11.8.6-MariaDB&charset=utf8mb4"


# Environnement
APP_ENV=dev
APP_SECRET=VOTRE_SECRET_ALEATOIRE
```

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create

### 5. Importer la structure SQL

```bash
mysql -u VOTRE_USER -p bibliotheque < sql/structure.sql
```

> Le fichier `sql/bilbiotheque_v2.sql` contient la structure complète des tables.

### 6. Créer le compte administrateur

Le compte administrateur ne peut pas être créé depuis l'application.  
Exécutez la commande suivante pour le créer manuellement :

```bash
php bin/console app:create-admin

Ou insérez-le directement en base :

```sql
INSERT INTO adminstrateur (login, mot_de_passe)
VALUES ('votre_nom', 'votre_mot_de_passe')

### 7. Démarrer le serveur de développement

```bash
# Avec Symfony CLI
symfony serve

# Ou avec PHP
php -S localhost:8000 -t public/
```

L'application est accessible sur : **http://localhost:8000**


