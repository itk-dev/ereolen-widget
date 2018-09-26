# Project setup

## Install

## Installation

### Environment variables

Set environment variables in your web server, e.g. for [Apache](https://httpd.apache.org/docs/2.4/mod/mod_env.html#setenv):

```sh
SetEnv APP_ENV prod
SetEnv APP_SECRET ChangeThis
SetEnv DATABASE_URL 'mysql://username:password@host:port/ereolen_widget
SetEnv CORS_ALLOW_ORIGIN '^https?://'
```

### Download dependencies and create database

```sh
composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

### Create users

```sh
bin/console fos:user:create --super-admin super-admin@example.com super-admin@example.com super-admin-password
bin/console fos:user:create admin@example.com admin@example.com admin-password
bin/console fos:user:promote admin@example.com ROLE_ADMIN
```

## Start/stop built-in web server

Start

```sh
bin/console server:run
```

Stop

```sh
bin/console server:stop
```

and go to [http://127.0.0.1:8000/](http://127.0.0.1:8000/).

## Build assets

Install yarn packages:

```sh
yarn install
```

Build for production:

```sh
yarn encore production
```

`git add` and `commit` changes in `public/build`.

During development:

```sh
yarn encore dev --watch
```
