# TuxBoy-Framework
[![Build Status](https://travis-ci.org/TuxBoy/TuxBoy-Framework.svg?branch=master)](https://travis-ci.org/TuxBoy/TuxBoy-Framework)

Un petit framework maison afin de m’entraîner et de me perfectionner en PHP.

## Setup

Pre-Requis :
- PHP >= 7.1
- MySQL database
 
Copier le fichier `.env.dist` en `.env` et ajouter votre configuration

Installer les dépendances

```bash
$ composer install
```

Démarrer le serveur de dev

```php
$ make server
```

Se rendre sur la page http://localhost:8000, La homepage est gérer par le module "src/Application/Home"
qui est la base d'un module du TuxBoy-Framework.

## Getting started

### Créer un "Module"

Pour créer un module, il suffit de créer un dossier dans le répertoire src/Application/YourModule/.
Un module doit être composé d'un fichier Application.php qui doit systématiqument implémenter : `TuxBoy\ApplicationInterface`

...

Lancer les tests unitaire

```php
$ phpunit
```

## Documentation
à finir : https://tuxboy-framework.readme.io

## TODO

### Core
- [X] Séparer l'ajout des routes dans une classe Router
- [ ] Ajouter un système de middleware (ou un système de plugin).
- [X] Intégrer GoPhp framework pour utiliser l'AOP (A voir dans l'usage).
- [X] Ajouter Whoops pour afficher mieux les erreurs.
- [X] Création d'un système de migration auto via doctrine dbal (Maintener).
- [X] Améliorer la partie Application.
- [X] Sépparer les vues et les mettres dans leur application.
- [X] Rendre une application (Module à voir pour le nom) autonome, que l'on puisse l'importer via composer
- [X] Mise en place de cake orm.
- [X] Créer l'annotation @length pour définir la taille d'un champ en base.
- [X] Créer (ou utiliser une lib) d'un form builder, générer un formulaire depuis une entité
- [ ] Améliorer la partie modularité en y ajoutant des test unitaires.
- [ ] Améliorer le générateur de formulaire via une entité.
- [ ] Rajouter une validation "automatique" suivant les options mis sur le formaulaire.
- [ ] Voir dans l'usage l'appelation "Application" afin de voir si c'est vraiment pertinent.
 
### Application

- [ ] Créer un module User à intégrer dans le Framework qui permet l'authentification.

### Documentation
- A faire !!
