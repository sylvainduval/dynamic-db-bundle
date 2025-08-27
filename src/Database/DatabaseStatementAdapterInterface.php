<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database;

/**
 * @internal
 */
interface DatabaseStatementAdapterInterface
{
	/**
	 * @param array<string, scalar|null> $params
	 */
	public function execute(array $params = []): bool;

	/**
	 * @return array<string, scalar|null>|false
	 */
	public function fetch(): array|false;

	/**
	 * @return array<mixed>
	 */
	public function fetchAll(): array;
}
