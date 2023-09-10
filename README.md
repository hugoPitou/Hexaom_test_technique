
# Hexaom Test Technique par Hugo Pitou

projet : Symfony 6.3 + PHP 8.2.10 avec BDD sous Docker

## Requirements

- PHP-8.2.10
- Composer
- Symfony 
- Docker

## Installation

Cloner le project

```bash
  git clone https://github.com/hugoPitou/hexaom_test.git
```

### Ouvrer un terminal dans le dossier du projet

Lancer le docker-compose.

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

Récuperer la url de la bdd, copié l'url qui se trouve sur cette ligne DATABASE_URL=

```bash
  symfony var:export --multiline
```

une fois url recupéré coller la dans le fichier .ENV comme indiquer:

```yaml
  DATABASE_URL="{url_récupéré}"
```

Retourner sur la console et exécuter la migration.

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