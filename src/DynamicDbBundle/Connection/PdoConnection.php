<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Connection;

use PDO;
use PDOException;
use SylvainDuval\DynamicDbBundle\Domain\Configuration;
use SylvainDuval\DynamicDbBundle\Exception\ConnectionException;

/**
 * @internal
 */
final class PdoConnection implements ConnectionInterface
{
	public function __construct(
		private readonly Configuration $configuration
	) {}

	public static function support(): bool
	{
		return \class_exists(PDO::class);
	}

	public function connect(): PDO
	{
		try {
			return new PDO(
				$this->configuration->getDsn(),
				$this->configuration->user,
				$this->configuration->password,
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				]
			);
		} catch (PDOException $e) {
			throw new ConnectionException('PDO connection failure: ' . $e->getMessage(), 0, $e);
		}
	}
}
