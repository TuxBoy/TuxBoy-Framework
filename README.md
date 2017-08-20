# TuxBoy-Framework
[![Build Status](https://travis-ci.org/TuxBoy/TuxBoy-Framework.svg?branch=master)](https://travis-ci.org/TuxBoy/TuxBoy-Framework)

Un petit framework maison afin de m’entraîner et de me perfectionner en PHP

## Setup

Pre-Requis :
- PHP >= 7.1
- MySQL database
 
Copier le fichier `.env.dist` en `.env` and ajouter votre configuration

```bash
$ composer install
```

Démarrer le serveur de dev

```php
$ make server
```

## TODO

- [X] Séparer l'ajout des routes dans une classe Router
- [ ] Ajouter Whoops pour afficher mieux les erreurs.
- [ ] Intégrer Phinx pour gérer les migrations.
- [ ] Continuer l'intégration de Doctrine/Dbal 