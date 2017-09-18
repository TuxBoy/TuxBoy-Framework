# TuxBoy-Framework
[![Build Status](https://travis-ci.org/TuxBoy/TuxBoy-Framework.svg?branch=master)](https://travis-ci.org/TuxBoy/TuxBoy-Framework)

Un petit framework maison afin de m’entraîner et de me perfectionner en PHP

## Setup

Pre-Requis :
- PHP >= 7.1
- MySQL database
 
Copier le fichier `.env.dist` en `.env` and ajouter votre configuration

Installer les dépendances

```bash
$ composer install
```

Démarrer le serveur de dev

```php
$ make server
```

Lancer les tests unitaire

```php
$ phpunit
```

## Documentation
à finir : https://tuxboy-framework.readme.io

## TODO

- [X] Séparer l'ajout des routes dans une classe Router
- [ ] Ajouter un système de middleware (ou un système de plugin).
- [X] Intégrer GoPhp framework pour utiliser l'AOP (A voir dans l'usage).
- [X] Ajouter Whoops pour afficher mieux les erreurs.
- [X] Création d'un système de migration auto via doctrine dbal (Maintener).
- [X] Améliorer la partie Application.
- [X] Sépparer les vues et les mettres dans leur application.
- [ ] Rendre une application (Module à voir pour le nom) autonome, que l'on puisse l'importer via composer
- [X] Mise en place de cake orm.
- [ ] Créer l'annotation @length pour définir la taille d'un champ en base.
- [ ] Créer (ou utiliser une lib) d'un form builder, générer un formulaire depuis une entité
