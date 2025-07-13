<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Enum;

/**
 * @internal
 */
enum Driver: string
{
	case MariaDB = 'mariadb';
	case MySQL = 'mysql';
	case PostgreSQL = 'pgsql';
}
