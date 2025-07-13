<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Enum;

/**
 * @internal
 */
enum Server
{
	case MariaDB;
	case MySQL;
	case PostgreSQL;
}
