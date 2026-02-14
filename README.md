# DynamicDB

**Object-Oriented Database Schema Management for PHP**

## Description

**DynamicDB** is a lightweight and expressive PHP bundle that enables you to manage database schemas dynamically through an object-oriented API.

Unlike traditional ORMs or migration systems that rely on static entity definitions and diffs, DynamicDB gives you full control over your schema operations — explicitly and programmatically.

This makes it ideal for applications where the database structure must adapt to dynamic or user-defined data models, such as:

* Product Information Management (PIM) systems
* Digital Asset Management (DAM) tools
* Reporting or analytics platforms
* Data mining or ETL tools
* Any schema-on-the-fly use case

With DynamicDB, you can:

* Create and drop databases or tables programmatically
* Define fields using PHP objects and set their attributes
* Add, modify or delete fields explicitly
* Run schema operations in install scripts, migrations, or runtime processes

## Key Principles & Advantages

### Clear Responsibilities

* You decide what should happen: create, modify, or delete.
* No hidden diffs or magic. DynamicDB executes exactly what you tell it.

### Fluent and Readable API

* Object-oriented syntax makes your schema definition self-documented.
* Easy to version, organize, and replay (e.g. in migrations or CLI scripts).

### Method-Based Flexibility

Core operations are exposed via stable and testable methods:
* createDatabase()
* deleteDatabase()
* createTable()
* createFields()
* changeField()
* deleteField()
* ...

Each schema component is represented as an object (Database, Table, Field), making it easy to encapsulate logic and manipulate structure dynamically. Database-specific settings for MySQL and PostgreSQL can be provided via dedicated options domain objects.

### Lazy loading

No connection is opened, and no SQL is executed, until the first operation is explicitly called. This allows for deferred execution and better control over when and how the database is accessed.

## Getting Started

### Dependencies

* PHP >= 8.4
* PDO or mysqli driver
* MySQL >= 5.7, MariaDB >= 10.7 or PostgreSQL

Also suggested requirements for dependency injection :

* psr/container: Required if you use standalone bridge
* symfony/dependency-injection: Required if you use Symfony

### Development Setup with Docker

For development, this project includes a complete Docker environment with PHP 8.4, MySQL, MariaDB, and PostgreSQL.

#### Prerequisites

* Docker and Docker Compose v2 installed on your system

#### Quick Start

1. Clone the repository and navigate to the project directory

2. Start the Docker environment:
```bash
make up
```

3. Install dependencies:
```bash
make install
```

4. Run tests to verify everything works:
```bash
make test
```

#### Available Make Commands

* `make help` - Show all available commands
* `make up` - Start all Docker services
* `make down` - Stop all Docker services
* `make shell` - Open a shell in the PHP container
* `make install` - Install Composer dependencies
* `make test` - Run all PHPUnit tests
* `make test-unit` - Run only unit tests
* `make test-integration` - Run only integration tests
* `make phpstan` - Run PHPStan static analysis
* `make cs-fix` - Fix code style with PHP-CS-Fixer
* `make cs-check` - Check code style without fixing
* `make clean` - Clean up containers, volumes, and caches

#### Manual Docker Commands

If you prefer not to use the Makefile:

```bash
# Start services
docker compose up -d

# Run tests
docker compose exec php vendor/bin/phpunit

# Run PHPStan
docker compose exec php vendor/bin/phpstan analyse

# Fix code style
docker compose exec php vendor/bin/php-cs-fixer fix
```

### Installing

1. Reference it in your composer.json:
```bash
composer require sylvainduval/dynamic-db-bundle
```

2. With Symfony

This bundle does not have a Symfony Flex recipe yet. You must add this line in config/bundles.php:
```php
<?php 

return [
	...
	SylvainDuval\DynamicDbBundle\Bridge\Symfony\DynamicDbSymfonyBundle::class => ['all' => true],
];
```
And set up your database connection in config/packages/dynamic_db.yaml:
```yaml
dynamic_db:
    host: 'db'
    port: 3306
    user: '***'
    password: '***'
    database: null
    charset: 'utf8mb4'
```

3. Without Symfony

Register the service in your own PSR container :
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

$container = new App\Container();

$bundle = new \SylvainDuval\DynamicDbBundle\Bridge\Standalone\DynamicDbRegistrar();
$bundle->register([
    'host' => 'db',
    'port' => 3306,
    'user' => '***',
    'password' => '***',
    'database' => null,
    'charset' => 'utf8mb4'
], $container);
```

## Todo

### Global:
- Improve exceptions and add query context in it

### MySQL / MariaDB:
- Foreign keys contraints
- autoincrement UNIQUE, not only PRIMARY KEY
- Others database and table options
- ENUM field type
- DATE and DATETIME field: support option with timezone
- Add field: after / before
- ...

### PostgreSQL:
- Schema management
- Foreign keys contraints
- Others database and table options
- ENUM field type
- JSON field: supports option binary version (JSONB)
- GEOMETRY and POINT fields: supports option for default value and SRID identifier
- DATE and DATETIME field: support option with timezone (TIMESTAMPTZ)
- Add field: after / before
- Change field: supports null / not null changes
- ...

## Authors

Contributors names and contact info

- [@sylvainduval](https://github.com/sylvainduval)

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.txt) file for more details.

## Acknowledgments

This project was inspired by several tools and concepts around schema versioning and database management:

* [Liquibase](https://www.liquibase.org/) – A powerful database schema change management tool that inspired the idea of declarative and trackable schema changes.
* [Doctrine Migrations](https://www.doctrine-project.org/projects/doctrine-migrations.html) – For its structured approach to PHP-based migrations.
* [Laravel Migrations](https://laravel.com/docs/migrations) – For its clean, fluent API to define schema changes in code.
* [Flyway](https://flywaydb.org/) – For its emphasis on versioned migrations and repeatability.

Special thanks to the open-source community for the many ideas and design patterns that influenced this bundle.
