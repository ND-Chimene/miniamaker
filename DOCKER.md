# Miniamaker - Docker

## Introduction

Ce projet utilise Docker pour faciliter le développement et la gestion de l'environnement Symfony avec PHP, Nginx, MySQL et d'autres services nécessaires. Ce guide explique comment configurer et résoudre les problèmes courants, notamment ceux liés aux permissions dans le répertoire `var` et comment utiliser les fixtures pour peupler la base de données.

## Prérequis

- Docker installé sur votre machine
- Composer pour gérer les dépendances PHP
- Symfony CLI pour les commandes Symfony
- Doctrine Fixtures Bundle pour charger les données de test

## Lancer le projet

1. Clonez le dépôt :
   ```bash
   git clone <url-du-dépôt>
   cd <nom-du-dossier>
   ```

2. Démarrez les containers Docker avec `docker-compose` :
   ```bash
   docker-compose up -d
   ```

3. Vérifiez que les containers fonctionnent :
   ```bash
   docker ps
   ```

   Les services suivants doivent être en cours d'exécution :
   - PHP (`miniamaker-php-1`)
   - Nginx (`miniamaker-nginx-1`)
   - MySQL (`miniamaker-db-1`)
   - PhpMyAdmin (`miniamaker-phpmyadmin-1`)
   - Mailpit (`mailpit`)

## Accès aux interfaces

- Application Symfony : [http://localhost:8000](http://localhost:8000)
- Mailpit (emails capturés) : [http://localhost:8025](http://localhost:8025)
- PhpMyAdmin : [http://localhost:8081](http://localhost:8081)

## Résolution des problèmes de permissions

Si vous rencontrez une erreur 500 ou d'autres problèmes d'accès aux répertoires de cache ou de logs, vous devrez peut-être ajuster les permissions sur le répertoire `var`.

### Étapes pour résoudre les problèmes de permissions

1. **Accédez au conteneur PHP** :
   ```bash
   docker exec -it miniamaker-php-1 sh
   ```

2. **Vider le cache Symfony** :
   ```bash
   rm -rf var/cache/*
   ```

3. **Changer le propriétaire du répertoire `var` pour `www-data`** :
   ```bash
   chown -R www-data:www-data var
   ```

4. **Appliquer les bonnes permissions** :
   ```bash
   chmod -R 775 var
   ```

5. **Créer les dossiers manquants (`cache` et `log`)** :
   ```bash
   mkdir -p var/cache var/log
   ```

6. **Quitter le conteneur Docker** :
   ```bash
   exit
   ```

## Doctrine & Base de Données

### Créer la base de données

```bash
php bin/console doctrine:database:create
```

### Supprimer la base de données (si existante)

```bash
php bin/console doctrine:database:drop --force
```

### Générer une migration

```bash
php bin/console make:migration
```

### Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

## Utilisation des Fixtures

Les **fixtures** sont des données de test utilisées pour peupler la base de données pendant le développement.

### Installer le bundle de fixtures

```bash
composer require --dev doctrine/doctrine-fixtures-bundle
```

### Charger les fixtures

```bash
php bin/console doctrine:fixtures:load
```

Ou sans confirmation :

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

## Commandes utiles

### Accéder à un conteneur en cours d'exécution

```bash
docker exec -it miniamaker-php-1 sh
```

### Vérification du statut des services

```bash
docker-compose ps
```

## Services utilisés

- **PHP-FPM** : Pour exécuter l'application Symfony.
- **Nginx** : Comme serveur web pour faire tourner l'application.
- **MySQL** : Base de données pour stocker les informations.
- **PhpMyAdmin** : Interface graphique pour gérer MySQL.
- **Mailpit** : Pour capturer et afficher les emails envoyés en développement.

## Conclusion

Avec Docker, vous pouvez rapidement configurer un environnement de développement Symfony avec tous les services nécessaires. Si vous rencontrez des problèmes de permissions ou de configuration, suivez les étapes ci-dessus pour résoudre les erreurs courantes liées au répertoire `var` et au cache. N'oubliez pas d'utiliser les fixtures pour faciliter le peuplement de votre base de données pendant le développement.