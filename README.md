# Miniamaker

Miniamaker est une plateforme qui permet aux graphistes spécialisés dans la conception de miniatures pour les vidéos de proposer leurs services à des particuliers, des professionnels et à des agences. La création d'un compte et la souscription abonnement annuel et mensuel sont  proposé et géré par Stripe pour avoir accès à toutes les fonctionnalités comme prendre contact avec le miniamaker, l'accès à différents prestataires présents.

## Fonctionnalités

* Création de contenu
* Partage de contenu
* Gestion des droits d'accès
* Système de messagerie
* Système de recherche
* Intégration avec les réseaux sociaux

## Technologies utilisées

* Symfony 6
* Twig
* Bootstrap 5
* JavaScript
* MySQL

## Installation

### Étape 1: Cloner le projet

Ouvrez votre terminal et tapez la commande suivante pour cloner le projet:

    git clone https://github.com/ND-Chimene/miniamaker.git

### Étape 2: Installer les dépendances

Tapez la commande suivante pour installer les dépendances:

    composer install

### Étape 3: Ajouter les variables d'environnement dans un fichier .env

* Base de donnée
* Mailtrap
* L'URL de l'application
* Stripe

### Étape 4: Créer la base de données

Tapez la commande suivante pour créer la base de données:

    symfony doctrine:database:create
    symfony doctrine:migrations:migrate

### Étape 5: Lancer le serveur

Tapez la commande suivante pour lancer le serveur:

    symfony server:start
