<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Connection;

use mysqli;
use PDO;

/**
 * @internal
 */
interface ConnectionInterface
{
	public static function support(): bool;

	public function connect(): PDO|mysqli;
}
