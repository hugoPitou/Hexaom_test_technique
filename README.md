
# Hexaom Test Technique par Hugo Pitou

Projet : Symfony 6.3 + PHP 8.2.10 avec BDD sous Docker

## Requirements

- PHP-8.2.10
- Composer
- Symfony 
- Docker

## Installation

Cloner le projet

```bash
  git clone https://github.com/hugoPitou/hexaom_test.git
```

### Ouvrir un terminal dans le dossier du projet

Lancer docker-compose.

```bash
  docker-compose up -d
```

Faire un composer install

```bash
  composer install
```
Faire un npm install pour les modules nodes
```bash
  npm install
```
Lancer la compilation de ressources front-end

```bash
  npm run build
```

Récupérer l'url de la base de données, copier l'url qui se trouve sur cette ligne DATABASE_URL=

```bash
  symfony var:export --multiline
```

une fois l'url recupérée, coller-la dans le fichier .ENV comme indiqué ci-dessous:

```yaml
  DATABASE_URL="{url_récupéré}"
```

Retourner sur la console et exécuter la migration 

```bash
  php bin/console d:m:m
```

Lancer le serveur interne de Symfony
```bash
  symfony serve -d
```
Votre application est disponible à l'adresse : http://127.0.0.1:8000

## Prêt à l'emploi

## Auteur

- [@hugopitou](https://github.com/hugoPitou)