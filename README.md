# An API Application Skeleton

This package contains the following libraries and tools.

* [Aura.Router v2](https://github.com/auraphp/Aura.Router/tree/2.x) A web router implementation for PHP. 
* [Aura.Sql v2](https://github.com/auraphp/Aura.Sql) Adapters for SQL database access 
* [Aura.SqlQuery v2](https://github.com/auraphp/Aura.SqlQuery) Independent query builders for MySQL, PostgreSQL, SQLite, and Microsoft SQL Server.
* [Koriym.QueryLocator](https://github.com/koriym/Koriym.QueryLocator) SQL locator
* [Phinx](https://phinx.org/) Database migrations 

# Installation

```
composer create-project bear/skeleton {project-path} dev-api
```

# Configuration

## Database connection

`.env`

    DB_DSN=mysql:host=localhost;dbname=task
    DB_USER=root
    DB_PASS=
    DB_READ=

## Create databse

    php bin/create_db.php 

## Database migrations

Create migration.

    php vendor/bin/phinx create -c var/db/phinx.php MyNewMigration  


Perform migration.

    php vendor/bin/phinx migrate -c var/db/phinx.php

# Route

`var/conf/aura.route.php`

```php
<?php
/** @var $router \BEAR\Package\Provide\Router\AuraRoute */
$router->route('/task', '/task/{id}');
```

# Tutorial 

http://bearsunday.github.io/manuals/1.0/ja/quick-api.html