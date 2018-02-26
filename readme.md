# Filestash Module

Documentation du module de système de fichier

## Requirements

This package uses some functionnalities of the lmod-authz or lmod-authz-ldap package for 
the admin protection ( middleware ) or for the group managements ( files )

## Fonctionnalités

### Stockage de fichier
- Système de fichier
-- Upload
-- Download
-- Copier / Coller
-- Deplacer
-- Supprimer
-- Renomer
- Arborecence définie mais modifable (seeder folder)
- Gestion des droits basée sur les groupes (lmod-authz lmod-authz-ldap)
- Système de collection (liens symboliques (favoris))
- Recherche simple ( all )
- Recherche avancée ( nom / ext / date / periode / ... )
- Tag ( policy / workflows / permissions )

## Install

Begin by installing this package through Composer. Edit your project's composer.json file to require laravelcollective/html.

`composer require league/flysystem`

`composer require league/climate`

`composer require league/fractal`

Next, add your new provider to the providers array of config/app.php:

```php
  'providers' => [
    // ...
    Irisit\IrisFS\FilesystemServiceProvider::class,
    // ...
  ],
```