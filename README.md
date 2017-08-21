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
- [ ] Intégrer GoPhp framework pour utiliser l'AOP.
- [X] Ajouter Whoops pour afficher mieux les erreurs.
- [ ] Intégrer Phinx pour gérer les migrations.
- [ ] Continuer l'intégration de Doctrine/Dbal 
