# ECF - Part 1 - Projet bibliothèque - BDD

Le but de cet exercice est de maîtriser la création d'une base de données (BDD) qui sera utilisée dans une application web dynamique.

## Install

    git clone https://github.com/pierrooTH/ECF-library-project
    cd ECF-library-project
    composer install

Après install du projet, créer le fichier `.env.local` et ajoutez-y les variables `APP_ENV` et `DATABASE_URL`.

Créer la BDD avec PhpMyAdmin.

Ensuite créer le schéma de la BDD et injectez les données de test avec la commande :

    bin/dofilo.sh

## Utilisation

    symfony serve

Ensuite visitez la page [http://localhost:8000](http://localhost:8000).

## Cahier des charges

Création d'une BDD qui implémente la structure et les données ci-dessous.

Pour la création de la BDD, Doctrine (symfony) a été choisi.

## Prérequis

- MariaDB
- PHPMyAdmin
- PHP 7.x ou 8.x
- composer
- Symfony

## Structure de la BDD, données indispensables et données de test

### User

Attributs :

- id : clé primaire
- email : varchar 190
- roles : text
- password : varchar 190

Relations :

- aucune

Données de test : 100 users dont les données sont générées aléatoirement

### Livre

Attributs :

- id : clé primaire
- titre : varchar 190
- annee_edition : int, nullable
- nombre_pages : int
- code_isbn : varchar 190, nullable

Relations :

- auteur : many to one
- genres : many to many
- emprunts : one to many

Données de test : 1000 livres dont les données sont générées aléatoirement.
N'oubliez pas créer également les relations.

### Auteur

Attributs :

- id : clé primaire
- nom : varchar 190
- prenom : varchar 190

Relations :

- livres : one to many

Données de test : 500 auteurs dont les données sont générées aléatoirement

### Genre

Attributs :

- id : clé primaire
- nom : varchar 190
- description : text, nullable

Relations :

- livres : many to many

### Emprunteur

Attributs :

- id : clé primaire
- nom : varchar 190
- prenom : varchar 190
- tel : varchar 190
- actif : boolean
- date_creation : datetime
- date_modification : datetime, nullable

Relations :

- emprunts : one to many
- user : one to one, unidirectionnel

Données de test : 100 emprunteurs dont les données sont générées aléatoirement

### Emprunt

Attributs :

- id : clé primaire
- date_emprunt : datetime
- date_retour : datetime, nullable

Relations :

- emprunteur : many to one
- livre : many to one

Données de test : 200 emprunts dont les données sont générées aléatoirement
