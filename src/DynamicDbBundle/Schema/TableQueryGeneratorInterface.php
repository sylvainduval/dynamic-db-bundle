<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Index;
use SylvainDuval\DynamicDbBundle\Domain\Table;

/**
 * @internal
 */
interface TableQueryGeneratorInterface
{
	public function generateCreateTable(Table $table): string;

	public function generateDeleteTable(Table $table): string;

	public function generateCreateIndex(Table $table, Index $index): string;

	public function generateDeleteIndex(Table $table, string $indexName): string;
}
