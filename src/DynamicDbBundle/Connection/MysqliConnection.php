<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Connection;

use mysqli;
use SylvainDuval\DynamicDbBundle\Domain\Configuration;
use SylvainDuval\DynamicDbBundle\Exception\ConnectionException;

/**
 * @internal
 */
final class MysqliConnection implements ConnectionInterface
{
	public function __construct(
		private readonly Configuration $configuration
	) {}

	public static function support(): bool
	{
		return \class_exists(mysqli::class);
	}

	public function connect(): mysqli
	{
		$mysqli = new mysqli(
			$this->configuration->host,
			$this->configuration->user,
			$this->configuration->password,
			$this->configuration->database,
			$this->configuration->port
		);

		if ($mysqli->connect_error) {
			throw new ConnectionException('Mysqli connection failure: ' . $mysqli->connect_error);
		}

		return $mysqli;
	}
}
