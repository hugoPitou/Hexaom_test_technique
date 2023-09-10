
# Symfony 6.3.4 + PHP 8.2.10 avec Docker database

**Installation**

## Run Locally

Clone the project

```bash
  git clone https://github.com/hugoPitou/hexaom_test.git
```

Run the docker-compose 

*Si images deja existante faire que docker-compose up -d*

```bash
  docker-compose build
  docker-compose up -d
```

Lancer le serveur interne de Symfony

```bash
  symfony serve -d
```
*Your application is available at http://127.0.0.1:8000*

