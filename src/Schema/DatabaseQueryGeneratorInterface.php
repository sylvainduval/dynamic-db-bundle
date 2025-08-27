<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Database;

/**
 * @internal
 */
interface DatabaseQueryGeneratorInterface
{
	public function generateCreateDatabase(Database $database): string;

	public function generateDeleteDatabase(Database $database): string;
}
