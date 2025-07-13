<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain;

use SylvainDuval\DynamicDbBundle\Domain\Enum\Driver;

/**
 * @phpstan-type ConfigurationArray array{driver: string, host: string, port: int, user: string, password: string, database?: ?string, charset?: ?string}
 * @internal
 */
final class Configuration
{
	private function __construct(
		public readonly Driver $driver,
		public readonly string $host,
		public readonly int $port,
		public readonly string $user,
		public readonly string $password,
		public ?string $database,
		public readonly ?string $charset,
	) {}

	/**
	 * @param ConfigurationArray $config
	 */
	public static function fromArray(array $config): self
	{
		return new self(
			Driver::from($config['driver']),
			$config['host'],
			$config['port'],
			$config['user'],
			$config['password'],
			$config['database'] ?? null,
			$config['charset'] ?? null,
		);
	}

	public function getDsn(): string
	{
		$dsn = \sprintf(
			'%s:host=%s;port=%d',
			$this->driver->value,
			$this->host,
			$this->port,
		);
		if ($this->database !== null) {
			$dsn .= ';dbname=' . $this->database;
		}
		if ($this->charset !== null && $this->driver !== Driver::PostgreSQL) {
			$dsn .= ';charset=' . $this->charset;
		}

		return $dsn;
	}

	public function withDatabase(?string $database): Configuration
	{
		$config = clone $this;
		$config->database = $database;

		return $config;
	}
}
