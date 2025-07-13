<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Database;

/**
 * @internal
 */
interface DatabaseClosableConnectionsInterface
{
	public function generateCloseConnections(Database $database): string;
}
