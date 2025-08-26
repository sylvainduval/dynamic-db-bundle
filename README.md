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
* psr/container ^2.0
* PDO or mysqli driver
* MySQL >= 5.7, MariaDB >= 10.7 or PostgreSQL

### Installing

DynamicDB is currently in development and not yet published on Packagist. To use it locally in your project:

1. Clone the repository somewhere on your machine:

```bash
git clone git@github.com:sylvainduval/dynamic-db-bundle.git /opt/DynamicDbBundle
```

2. Reference it in your composer.json using the path repository type:
```json
{
  "repositories": [
    {
      "type": "path",
      "url": "/opt/DynamicDbBundle",
      "options": {
        "symlink": true
      }
    }
  ],
  "require": {
    "sylvainduval/dynamic-db-bundle": "*"
  }
}
```

3. Install the dependencies:

```bash
composer update sylvainduval/dynamic-db-bundle
```

ℹ️ The symlink option allows you to edit the bundle source code live and see changes reflected in your consuming project.

## Todo

### Global:
- Symfony service injection
- Tests

### MySQL / MariaDB:
- Contraints
- Foreign keys
- Indexes (including SPACIAL)
- autoincrement UNIQUE instead of PRIMARY KEY
- Others database and table options
- ENUM field type
- DATE and DATETIME field: support option with timezone
- Add field: after / before
- ...

### PostgreSQL:
- Schema management
- Contraints
- Foreign keys
- Indexes (including SPACIAL)
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

## Version History

* 0.0.0
    * Unreleased

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.txt) file for more details.

## Acknowledgments

This project was inspired by several tools and concepts around schema versioning and database management:

* [Liquibase](https://www.liquibase.org/) – A powerful database schema change management tool that inspired the idea of declarative and trackable schema changes.
* [Doctrine Migrations](https://www.doctrine-project.org/projects/doctrine-migrations.html) – For its structured approach to PHP-based migrations.
* [Laravel Migrations](https://laravel.com/docs/migrations) – For its clean, fluent API to define schema changes in code.
* [Flyway](https://flywaydb.org/) – For its emphasis on versioned migrations and repeatability.

Special thanks to the open-source community for the many ideas and design patterns that influenced this bundle.