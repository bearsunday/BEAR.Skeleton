# BEAR.Skeleton
![Static Analysis](https://github.com/bearsunday/BEAR.Skeleton/workflows/Static%20Analysis/badge.svg)

# Introduction

This is a skeleton application using the BEAR.Sunday framework.


# Installation using Composer

To create your new BEAR.Sunday project:

```
composer create-project bear/skeleton path/to/install
```

* Documentation http://bearsunday.github.io/

# Docker development

Build the development image and install dependencies inside the container:

```bash
docker compose build
docker compose run --rm app composer install
```

Start the built-in PHP server:

```bash
docker compose up
```

The app is available at http://127.0.0.1:8080/. You can also run CLI requests through Docker:

```bash
docker compose run --rm app php bin/page.php get /
```

Xdebug is installed but disabled by default. Enable it only when debugging:

```bash
XDEBUG_MODE=debug,develop XDEBUG_START_WITH_REQUEST=yes docker compose up
```

For CLI debugging:

```bash
XDEBUG_MODE=debug,develop XDEBUG_START_WITH_REQUEST=yes docker compose run --rm app php bin/page.php get /
```

Use port `9003` in your IDE and map the project root to `/app`. The default `PHP_IDE_CONFIG` server name is `BEAR.Skeleton`.

## How to test the skeleton itself

1. Make sure every files are commited.
2. Run `composer update` to emulate `create-project`.
3. See the created project files.
4. Run `git reset --hard HEAD` to be recovered.
